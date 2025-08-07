<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include('./server/connection.php');

if (!isset($_SESSION['user_id'])) {
  die('You must be logged in to view your orders.');
}
$loggedInUserId = $_SESSION['user_id'];

$pendingQuery = "SELECT COUNT(*) AS pending_count FROM orders WHERE status = 'Pending' AND user_id = ?";
$pendingStmt = mysqli_prepare($conn, $pendingQuery);
mysqli_stmt_bind_param($pendingStmt, 's', $loggedInUserId);
mysqli_stmt_execute($pendingStmt);
$pendingResult = mysqli_stmt_get_result($pendingStmt);
$pendingCount = mysqli_fetch_assoc($pendingResult)['pending_count'];

$conditions = ["user_id = ?"];
$params = [$loggedInUserId];

if (!empty($_GET['order_id'])) {
    $conditions[] = "id = ?";
    $params[] = $_GET['order_id'];
}

if (!empty($_GET['created_at'])) {
    $conditions[] = "DATE(created_at) = ?";
    $params[] = $_GET['created_at'];
}

if (!empty($_GET['status'])) {
    $conditions[] = "status = ?";
    $params[] = $_GET['status'];
}

$sql = "SELECT * FROM orders WHERE " . implode(" AND ", $conditions) . " ORDER BY id DESC";
$stmt = mysqli_prepare($conn, $sql);
if (!empty($params)) {
    $types = str_repeat('s', count($params));
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Initialize arrays to prevent undefined warnings
$orderItems = [];
$orderIds = [];
$orders = [];

while ($row = mysqli_fetch_assoc($result)) {
    $orderIds[] = $row['id'];
    $orders[] = $row;
}

if (!empty($orderIds)) {
    $placeholders = implode(',', array_fill(0, count($orderIds), '?'));
    $types = str_repeat('i', count($orderIds));
    $stmtItems = mysqli_prepare($conn, "SELECT * FROM order_items WHERE order_id IN ($placeholders)");
    mysqli_stmt_bind_param($stmtItems, $types, ...$orderIds);
    mysqli_stmt_execute($stmtItems);
    $itemsResult = mysqli_stmt_get_result($stmtItems);
    while ($item = mysqli_fetch_assoc($itemsResult)) {
        $orderItems[$item['order_id']][] = $item;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>My Orders</title>
  <link rel="stylesheet" href="./css/style.css">
  <script src="https://cdn.tailwindcss.com"></script>

</head>
<body>
<div class="area">
			<ul class="circles">
				<li></li>
				<li></li>
				<li></li>
				<li></li>
				<li></li>
				<li></li>
				<li></li>
				<li></li>
				<li></li>
				<li></li>
			</ul>
		</div>
<div class="mx-4 min-h-screen max-w-screen-xl sm:mx-8 xl:mx-auto">
 <div class="flex border-b justify-between items-center">
  <h1 class="py-6 text-4xl font-semibold">My Orders</h1>
  <a href="/FN/e-commerce/homepage.php" class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" aria-label="Close menu">
    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" >
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
    </svg>
  </a>
 </div>

  <div class="grid grid-cols-1 sm:grid-cols-10 pt-10">
    <div class="col-span-2 hidden sm:block">
      <ul>
        <li class="mt-5 cursor-pointer border-l-2 border-transparent px-2 py-2 font-semibold transition hover:border-l-blue-700 hover:text-blue-700"><a href="./setting.php">Accounts</a></li>
        <li class="mt-5 cursor-pointer border-l-2 border-transparent px-2 py-2 font-semibold transition hover:border-l-blue-700 hover:text-blue-700"><a href="./profile.php">Profile</a></li>
        <li class="mt-5 cursor-pointer border-l-2 border-transparent px-2 py-2 font-semibold transition hover:border-l-blue-700 hover:text-blue-700"><a href="./order_user.php">Your Order</a></li>
      </ul>
    </div>

    <div class="col-span-8 overflow-hidden rounded-xl  sm:px-8 sm:shadow">
      <div class="max-w-7xl mx-auto py-8 px-4">
        <h1 class="text-3xl font-bold mb-6">Order</h1>

        <div class="bg-yellow-100 text-yellow-800 p-4 mb-6 rounded-lg border border-yellow-300 font-semibold">
          🔔 Your Pending Orders: <?= $pendingCount ?>
        </div>

        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
          <input type="text" name="order_id" placeholder="Order ID" value="<?= htmlspecialchars($_GET['order_id'] ?? '') ?>" class="border p-2 rounded">
          <input type="date" name="created_at" value="<?= htmlspecialchars($_GET['created_at'] ?? '') ?>" class="border p-2 rounded">
          <select name="status" class="border p-2 rounded">
            <option value="">All Status</option>
            <option value="Pending" <?= ($_GET['status'] ?? '') === 'Pending' ? 'selected' : '' ?>>Pending</option>
            <option value="Deliveried" <?= ($_GET['status'] ?? '') === 'Deliveried' ? 'selected' : '' ?>>Deliveried</option>
            <option value="Canceled" <?= ($_GET['status'] ?? '') === 'Canceled' ? 'selected' : '' ?>>Canceled</option>
          </select>
          <button type="submit" class="bg-pink-300 text-white px-4 py-2 rounded hover:bg-pink-400">Search</button>
        </form>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <?php if (empty($orders)): ?>
            <p class="p-6"></p>
          <?php else: ?>
            <?php foreach ($orders as $row): ?>
              <div class="grid rounded-lg shadow-lg p-5 gap-2 border border-pink-300" onclick="toggleItems(<?= $row['id'] ?>)" style="cursor:pointer;">
               
                <h2 class="text-xl font-semibold mb-2">User ID: <?= htmlspecialchars($row['user_id']) ?></h2>
                <p class="text-sm font-medium text-gray-700">Order ID: <?= htmlspecialchars($row['id']) ?></p>

                <p class="text-sm mb-1">Price: $<?= number_format($row['total'], 2) ?></p>
                <p class="text-sm mb-1">Status: 
                  <span class="<?= $row['status'] === 'Pending' ? 'text-orange-400' : ($row['status'] === 'Deliveried' ? 'text-green-400' : 'text-red-400') ?>">
                    <?= htmlspecialchars($row['status']) ?>
                  </span>
                </p>
                <p class="text-sm mb-1">Location: <?= htmlspecialchars($row['address']) ?></p>
                <p class="text-sm mb-1">Buy Date: <?= htmlspecialchars($row['created_at']) ?></p>

                <div id="items-<?= $row['id'] ?>" class="mt-4 hidden bg-pink-50 rounded-lg p-4">
                  <h3 class="font-semibold mb-2 text-pink-600">🛍️ Items Purchased:</h3>
                  <?php if (!empty($orderItems[$row['id']])): ?>
                    <ul class="space-y-3">
                      <?php foreach ($orderItems[$row['id']] as $item): ?>
                        <li class="flex items-center gap-4">
                          <img src="<?= htmlspecialchars($item['product_image']) ?>" alt="<?= htmlspecialchars($item['product_name']) ?>" class="w-12 h-12 rounded object-cover border border-gray-200" />
                          <div>
                            <p class="font-medium"><?= htmlspecialchars($item['product_name']) ?></p>
                            <p class="text-sm text-gray-600">Qty: <?= $item['quantity'] ?> | $<?= number_format($item['price'], 2) ?></p>
                          </div>
                        </li>
                      <?php endforeach; ?>
                    </ul>
                  <?php else: ?>
                    <p class="italic text-gray-400">No items found for this order.</p>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
  <script>
    function toggleItems(id) {
      const el = document.getElementById('items-' + id);
      el.style.display = el.style.display === 'block' ? 'none' : 'block';
    }
  </script>
</body>
</html>
