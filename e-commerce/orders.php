<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include('./server/connection.php');

// Get count of pending orders
$pendingQuery = "SELECT COUNT(*) AS pending_count FROM orders WHERE status = 'Pending'";
$pendingResult = mysqli_query($conn, $pendingQuery);
$pendingCount = mysqli_fetch_assoc($pendingResult)['pending_count'];

// Handle filters
$conditions = [];
$params = [];

// Filter by Order ID
if (!empty($_GET['order_id'])) {
    $conditions[] = "id = ?";
    $params[] = $_GET['order_id'];
}

// Filter by User ID
if (!empty($_GET['user_id'])) {
    $conditions[] = "user_id = ?";
    $params[] = $_GET['user_id'];
}

// Filter by Buy Date
if (!empty($_GET['created_at'])) {
    $conditions[] = "DATE(created_at) = ?";
    $params[] = $_GET['created_at'];
}

// Filter by Status
if (!empty($_GET['status'])) {
    $conditions[] = "status = ?";
    $params[] = $_GET['status'];
}

$sql = "SELECT * FROM orders";
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}
$sql .= " ORDER BY id DESC";

// Prepare and execute query
$stmt = mysqli_prepare($conn, $sql);
if (!empty($params)) {
    $types = str_repeat('s', count($params));
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Fetch order items grouped by order_id
$orderItems = [];
$itemQuery = "SELECT order_id, product_name, product_image, quantity, price FROM order_items";
$itemResult = mysqli_query($conn, $itemQuery);
while ($itemRow = mysqli_fetch_assoc($itemResult)) {
    $orderItems[$itemRow['order_id']][] = $itemRow;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Orders</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="./css/style.css">
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
  <h1 class="py-6 text-4xl font-semibold">Settings</h1>
  <a href="/FN/e-commerce/homepage.php" class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" aria-label="Close menu">
    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" >
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
    </svg>
  </a>
 </div>

  <div class="grid grid-cols-8 pt-10 sm:grid-cols-10">
    <!-- Sidebar -->
             <div class="relative my-4 w-56 sm:hidden">
      <input class="peer hidden" type="checkbox" name="select-1" id="select-1" />
      <label for="select-1" class="flex w-full cursor-pointer select-none rounded-lg border p-2 px-3 text-sm text-gray-700 ring-blue-700 peer-checked:ring">Accounts</label>
      <svg xmlns="http://www.w3.org/2000/svg" class="pointer-events-none absolute right-0 top-3 ml-auto mr-5 h-4 text-slate-700 transition peer-checked:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
      </svg>
 <ul class="max-h-0 select-none flex flex-col overflow-hidden rounded-b-lg shadow-md transition-all duration-300 peer-checked:max-h-72 peer-checked:py-3">
    <li class="cursor-pointer px-3 py-2 text-sm text-slate-600 hover:bg-blue-700 hover:text-white"><a href="./dashboard.php">Dash Board</a></li>
    <li class="cursor-pointer px-3 py-2 text-sm text-slate-600 hover:bg-blue-700 hover:text-white"><a href="./setting_admin.php">Accounts</a></li>
    <li class="cursor-pointer px-3 py-2 text-sm text-slate-600 hover:bg-blue-700 hover:text-white"><a href="./user_account.php">User Account</a></li>
    <li class="cursor-pointer px-3 py-2 text-sm text-slate-600 hover:bg-blue-700 hover:text-white"><a href="./profile_admin.php">Profile</a></li>
    <li class="cursor-pointer px-3 py-2 text-sm text-slate-600 hover:bg-blue-700 hover:text-white"><a href="./add_product.php">Products</a></li>
    <li class="cursor-pointer px-3 py-2 text-sm text-slate-600 hover:bg-blue-700 hover:text-white"><a href="./orders.php">Orders</a></li>
    <li class="cursor-pointer px-3 py-2 text-sm text-slate-600 hover:bg-blue-700 hover:text-white"><a href="./message.php">Messages</a></li>
  </ul>

    </div>
    <div class="col-span-2 hidden sm:block">
     <ul>
        <li class="mt-5 cursor-pointer border-l-2 border-transparent px-2 py-2 font-semibold transition hover:border-l-blue-700 hover:text-blue-700"><a href="./dashboard.php">Dash Board</a></li>
        <li class="mt-5 cursor-pointer border-l-2 border-transparent px-2 py-2 font-semibold transition hover:border-l-blue-700 hover:text-blue-700"><a href="setting_admin.php">Accounts</a></li>
        <li class="mt-5 cursor-pointer border-l-2 border-transparent px-2 py-2 font-semibold transition hover:border-l-blue-700 hover:text-blue-700"><a href="./user_account.php">User Account</a></li>
        <li class="mt-5 cursor-pointer border-l-2 border-transparent px-2 py-2 font-semibold transition hover:border-l-blue-700 hover:text-blue-700">
          <a href="./profile_admin.php">Profile</a>
        </li>
        <li class="mt-5 cursor-pointer border-l-2 border-transparent px-2 py-2 font-semibold transition hover:border-l-blue-700 hover:text-blue-700"><a href="./add_product.php">Products</a></li>
        <li class="mt-5 cursor-pointer border-l-2 border-l-blue-700 px-2 py-2 font-semibold text-blue-700 transition hover:border-l-blue-700 hover:text-blue-700"><a href="./orders.php">Orders</a></li>
         
         <li class="mt-5 cursor-pointer border-l-2 border-transparent px-2 py-2 font-semibold transition hover:border-l-blue-700 hover:text-blue-700"><a href="./message.php">Messages</a></li>

        
      </ul>
    </div>

    <!-- Content Area -->
    <div class="col-span-8 overflow-hidden rounded-xl  sm:px-8 sm:shadow">
      <div class="max-w-7xl mx-auto py-8 px-4">
        <h1 class="text-3xl font-bold mb-6">Order</h1>

        <!-- Pending Orders Summary -->
        <div class="bg-yellow-100 text-yellow-800 p-4 mb-6 rounded-lg border border-yellow-300 font-semibold">
          🔔 Pending Orders: <?= $pendingCount ?>
        </div>

        <!-- Filter Form -->
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
          <input type="text" name="order_id" placeholder="Order ID" value="<?= htmlspecialchars($_GET['order_id'] ?? '') ?>" class="border p-2 rounded">
          <input type="text" name="user_id" placeholder="User ID" value="<?= htmlspecialchars($_GET['user_id'] ?? '') ?>" class="border p-2 rounded">
          <input type="date" name="created_at" value="<?= htmlspecialchars($_GET['created_at'] ?? '') ?>" class="border p-2 rounded">
          <select name="status" class="border p-2 rounded">
            <option value="">All Status</option>
            <option value="Pending" <?= ($_GET['status'] ?? '') === 'Pending' ? 'selected' : '' ?>>Pending</option>
            <option value="Deliveried" <?= ($_GET['status'] ?? '') === 'Deliveried' ? 'selected' : '' ?>>Deliveried</option>
            <option value="Canceled" <?= ($_GET['status'] ?? '') === 'Canceled' ? 'selected' : '' ?>>Canceled</option>
          </select>
          <button type="submit" class="bg-pink-300 text-white px-4 py-2 rounded hover:bg-pink-400">Search</button>
        </form>

        <!-- Orders List -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="grid rounded-lg shadow-lg p-5 gap-2 border border-pink-300 cursor-pointer" onclick="toggleItems(<?= $row['id'] ?>)">
              <h2 class="text-xl font-semibold mb-2">User ID: <?= htmlspecialchars($row['user_id']) ?></h2>
              <p class="text-sm text-gray-400 mb-1">Order ID: <?= htmlspecialchars($row['id']) ?></p>
              <p class="text-sm mb-1">Price: $<?= number_format($row['total'], 2) ?></p>
              <p class="text-sm mb-1">Status: 
                <span class="<?= $row['status'] === 'Pending' ? 'text-orange-400' : ($row['status'] === 'Deliveried' ? 'text-green-400' : 'text-red-400') ?>">
                  <?= htmlspecialchars($row['status']) ?>
                </span>
              </p>
              <p class="text-sm mb-1">Location: <?= htmlspecialchars($row['address']) ?></p>
              <p class="text-sm mb-1">Buy Date: <?= htmlspecialchars($row['created_at']) ?></p>

              <!-- Items List -->
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
                     
                    <p class=" text-black bg-black">No items found for this order.</p>
                  
                <?php endif; ?>
              </div>

              <!-- Update Status Form -->
              <form method="POST" action="update_order_status.php">
                <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                <select name="status" class="w-full p-2 rounded bg-pink-300 text-white mt-2">
                  <option value="Pending" <?= $row['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                  <option value="Deliveried" <?= $row['status'] == 'Deliveried' ? 'selected' : '' ?>>Deliveried</option>
                  <option value="Canceled" <?= $row['status'] == 'Canceled' ? 'selected' : '' ?>>Canceled</option>
                </select>
                <button type="submit" class="border border-pink-300 hover:bg-pink-300 hover:text-white text-pink-400 py-2 w-full rounded mt-4">
                  Save Changes
                </button>
              </form>
            </div>
          <?php endwhile; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Toggle Script -->
<script>
  function toggleItems(id) {
    const el = document.getElementById('items-' + id);
    el.style.display = el.style.display === 'block' ? 'none' : 'block';
  }
</script>
</body>
</html>
