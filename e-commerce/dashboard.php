<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include('./server/connection.php');

// Get Total Revenue
$revenueResult = mysqli_query($conn, "SELECT SUM(total) AS total_revenue FROM orders");
$revenueRow = mysqli_fetch_assoc($revenueResult);
$totalRevenue = $revenueRow['total_revenue'] ?? 0;

// Get Total Users
$userResult = mysqli_query($conn, "SELECT COUNT(*) AS total_users FROM users");
$userRow = mysqli_fetch_assoc($userResult);
$totalUsers = $userRow['total_users'] ?? 0;

// Get Total Orders
$orderResult = mysqli_query($conn, "SELECT COUNT(*) AS total_orders FROM orders");
$orderRow = mysqli_fetch_assoc($orderResult);
$totalOrders = $orderRow['total_orders'] ?? 0;

// Get Total Products Sold
$productResult = mysqli_query($conn, "SELECT SUM(quantity) AS total_products FROM order_items");
$productRow = mysqli_fetch_assoc($productResult);
$totalProducts = $productRow['total_products'] ?? 0;

// Get Top Products dynamically (assuming product_image in order_items)
$topProducts = [];
$topProductsQuery = "
    SELECT 
        p.name AS product_name,
        oi.product_image,
        p.price,
        SUM(oi.quantity) AS total_sold
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    GROUP BY oi.product_id, oi.product_image, p.name, p.price
    ORDER BY total_sold DESC
    LIMIT 4
";
$result = mysqli_query($conn, $topProductsQuery);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $topProducts[] = $row;
    }
}

// Get ALL sold products
$allProducts = [];
$allProductsQuery = "
    SELECT 
        p.name AS product_name,
        oi.product_image,
        p.price,
        SUM(oi.quantity) AS total_sold
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    GROUP BY oi.product_id, oi.product_image, p.name, p.price
    ORDER BY total_sold DESC
";
$resultAll = mysqli_query($conn, $allProductsQuery);
if ($resultAll) {
    while ($row = mysqli_fetch_assoc($resultAll)) {
        $allProducts[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cordes Admin Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="./css/style.css">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'cordes-blue': '#1e40af',
            'cordes-dark': '#1e293b',
            'cordes-light': '#f8fafc',
            'cordes-accent': '#3b82f6'
          }
        }
      }
    }
  </script>
</head>
<body class="">
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
<a href="/FN/e-commerce/homepage.php"
   class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
   aria-label="Close menu">
    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" >
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
    </svg>
</a>
 </div>

  <div class="grid grid-cols-8 pt-10 sm:grid-cols-10">
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
  <ul><li class="mt-5 cursor-pointer border-l-2 border-l-blue-700 px-2 py-2 font-semibold text-blue-700 transition hover:border-l-blue-700 hover:text-blue-700"><a href="./dashboard.php">Dash Board</a></li>
        <li class="mt-5 cursor-pointer border-l-2 border-transparent px-2 py-2 font-semibold transition hover:border-l-blue-700 hover:text-blue-700"><a href="setting_admin.php">Accounts</a></li>
        <li class="mt-5 cursor-pointer border-l-2 border-transparent px-2 py-2 font-semibold transition hover:border-l-blue-700 hover:text-blue-700"><a href="./user_account.php">User Account</a></li>
        <li class="mt-5 cursor-pointer border-l-2 border-transparent px-2 py-2 font-semibold transition hover:border-l-blue-700 hover:text-blue-700">
          <a href="./profile_admin.php">Profile</a>
        </li>
        <li class="mt-5 cursor-pointer border-l-2 border-transparent px-2 py-2 font-semibold transition hover:border-l-blue-700 hover:text-blue-700"><a href="./add_product.php">Products</a></li>
         <li class="mt-5 cursor-pointer border-l-2 border-transparent px-2 py-2 font-semibold transition hover:border-l-blue-700 hover:text-blue-700"><a href="./orders.php">Orders</a></li>
         <li class="mt-5 cursor-pointer border-l-2 border-transparent px-2 py-2 font-semibold transition hover:border-l-blue-700 hover:text-blue-700"><a href="./message.php">Messages</a></li>

        
      </ul>
    </div>

    <!-- Main -->
    <div class="col-span-8 overflow-hidden rounded-xl  sm:px-8 sm:shadow py-8">
      <!-- Header -->
      <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-900"><span class="text-pink-400">Dashboard</span> Overview</h2>
        <p class="text-sm text-gray-500 mt-1">Welcome back, here's what's happening today</p>
      </div>

      <!-- Stats Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Revenue -->
        <div class=" border rounded-lg shadow-sm p-5 hover:shadow-md transition">
          <div class="flex justify-between items-center">
            <div>
              <p class="text-sm text-gray-500">Total Revenue</p>
              <p class="text-2xl font-bold mt-2 text-gray-800" data-animate-count data-value="<?= intval($totalRevenue) ?>" data-prefix="$" data-suffix=".00">0</p>
            </div>
            <div class="w-10 h-10 flex items-center mt-6  justify-center bg-blue-100 rounded">
              <i class="fas fa-dollar-sign text-blue-700"></i>
            </div>
          </div>
        </div>

        <!-- Users -->
        <div class=" border rounded-lg shadow-sm p-5 hover:shadow-md transition">
          <div class="flex justify-between items-center">
            <div>
              <p class="text-sm text-gray-500">Total Users</p>
              <p class="text-2xl font-bold mt-2 text-gray-800" data-animate-count data-value="<?= intval($totalUsers) ?>" data-prefix="" data-suffix="">0</p>
            </div>
            <div class="w-10 h-10 flex items-center mt-6  justify-center bg-green-100 rounded">
              <i class="fas fa-users text-green-600"></i>
            </div>
          </div>
        </div>

        <!-- Orders -->
        <div class=" border rounded-lg shadow-sm p-5 hover:shadow-md transition">
          <div class="flex justify-between items-center">
            <div>
              <p class="text-sm text-gray-500">Total Orders</p>
              <p class="text-2xl font-bold mt-2 text-gray-800" data-animate-count data-value="<?= intval($totalOrders) ?>" data-prefix="" data-suffix="">0</p>
            </div>
            <div class="w-10 h-10 flex items-center mt-6  justify-center bg-orange-100 rounded">
              <i class="fas fa-shopping-cart text-orange-600"></i>
            </div>
          </div>
        </div>

        <!-- Products -->
        <div class=" border rounded-lg shadow-sm p-5 hover:shadow-md transition">
          <div class="flex justify-between items-center">
            <div>
              <p class="text-sm text-gray-500">Products Sold</p>
              <p class="text-2xl font-bold mt-2 text-gray-800" data-animate-count data-value="<?= intval($totalProducts) ?>" data-prefix="" data-suffix="">0</p>
            </div>
            <div class="w-10 h-10 flex items-center mt-6 justify-center bg-purple-100 rounded">
              <i class="fas fa-box text-purple-600"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Top Products -->
      <div class=" rounded-xl shadow-sm border border-gray-200 p-6 mt-6">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-lg font-semibold text-gray-900">Top <span class="text-pink-400">Products</span></h3>
          <button id="toggleViewBtn" class="text-cordes-blue hover:text-cordes-dark text-sm font-medium">View All</button>
        </div>

        <div id="topProductsContainer" class="space-y-4">
          <?php foreach ($topProducts as $product): ?>
          <div class="flex items-center space-x-4">
            <img src="<?= htmlspecialchars($product['product_image']) ?>" class="w-12 h-12 rounded-lg object-cover" alt="Product Image">
            <div class="flex-1">
              <p class="font-medium text-gray-900"><?= htmlspecialchars($product['product_name']) ?></p>
              <p class="text-sm text-gray-600">Sold: <?= number_format($product['total_sold']) ?></p>
            </div>
            <div class="text-right">
              <p class="font-semibold text-gray-900">$<?= number_format($product['price'], 2) ?></p>
            </div>
          </div>
          <?php endforeach; ?>
        </div>

        <div id="allProductsContainer" class="space-y-4 hidden max-h-96 overflow-y-auto mt-4 border-t border-gray-200 pt-4">
          <?php foreach ($allProducts as $product): ?>
          <div class="flex items-center space-x-4">
            <img src="<?= htmlspecialchars($product['product_image']) ?>" class="w-12 h-12 rounded-lg object-cover" alt="Product Image">
            <div class="flex-1">
              <p class="font-medium text-gray-900"><?= htmlspecialchars($product['product_name']) ?></p>
              <p class="text-sm text-gray-600">Sold: <?= number_format($product['total_sold']) ?></p>
            </div>
            <div class="text-right">
              <p class="font-semibold text-gray-900">$<?= number_format($product['price'], 2) ?></p>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Animation Script -->
<script>
  // Toggle product view
  const toggleBtn = document.getElementById('toggleViewBtn');
  const topContainer = document.getElementById('topProductsContainer');
  const allContainer = document.getElementById('allProductsContainer');

  toggleBtn.addEventListener('click', () => {
    const isAllVisible = !allContainer.classList.contains('hidden');
    if (isAllVisible) {
      allContainer.classList.add('hidden');
      topContainer.classList.remove('hidden');
      toggleBtn.textContent = 'View All';
    } else {
      allContainer.classList.remove('hidden');
      topContainer.classList.add('hidden');
      toggleBtn.textContent = 'View Less';
    }
  });

  // Animate counters
  function animateCounter(el, target, duration = 1500) {
    let start = 0;
    let startTime = null;

    function step(timestamp) {
      if (!startTime) startTime = timestamp;
      const progress = timestamp - startTime;
      const val = Math.min(Math.floor((progress / duration) * target), target);
      el.textContent = el.dataset.prefix + val.toLocaleString() + el.dataset.suffix;
      if (val < target) {
        window.requestAnimationFrame(step);
      }
    }

    window.requestAnimationFrame(step);
  }

  document.querySelectorAll('[data-animate-count]').forEach(el => {
    const value = parseInt(el.dataset.value, 10);
    animateCounter(el, value);
  });
</script>

</body>
</html>
