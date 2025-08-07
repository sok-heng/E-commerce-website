<?php
require_once __DIR__ . '/server/connection.php';
session_start();

$user_email = $_SESSION['user_email'] ?? null;

if (!$user_email) {
    die("User not logged in.");
}

// ✅ Role check to block non-admins
$stmt = $conn->prepare("SELECT user_role FROM users WHERE user_email = ?");
$stmt->bind_param('s', $user_email);
$stmt->execute();
$stmt->bind_result($user_role);
$stmt->fetch();
$stmt->close();

if ($user_role !== 'admin') {
    header("Location: /FN/e-commerce/unauthorized.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';

    if (!$current_password || !$new_password) {
        header("Location: setting_admin.php?error=" . urlencode("Please fill in both password fields."));
        exit;
    }

    $stmt = $conn->prepare("SELECT user_password FROM users WHERE user_email = ?");
    $stmt->bind_param('s', $user_email);
    $stmt->execute();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();
    $stmt->close();

    if (!$hashed_password) {
        header("Location: setting_admin.php?error=" . urlencode("User not found."));
        exit;
    }

    if (!password_verify($current_password, $hashed_password)) {
        header("Location: setting_admin.php?error=" . urlencode("Current password is incorrect."));
        exit;
    }

    $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $update_stmt = $conn->prepare("UPDATE users SET user_password = ? WHERE user_email = ?");
    $update_stmt->bind_param('ss', $new_hashed_password, $user_email);

    if ($update_stmt->execute()) {
        $update_stmt->close();
        header("Location: setting_admin.php?success=" . urlencode("Password updated successfully."));
        exit;
    } else {
        $update_stmt->close();
        header("Location: setting_admin.php?error=" . urlencode("Failed to update password."));
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Settings - Change Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="./css/style.css">
    <style>
        * {
            font-family: 'Source Sans Pro';
        }
    </style>
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
     <ul>
        <li class="mt-5 cursor-pointer border-l-2 border-transparent px-2 py-2 font-semibold transition hover:border-l-blue-700 hover:text-blue-700"><a href="./dashboard.php">Dash Board</a></li>
        <li class="mt-5 cursor-pointer border-l-2 border-l-blue-700 px-2 py-2 font-semibold text-blue-700 transition hover:border-l-blue-700 hover:text-blue-700"><a href="setting_admin.php">Accounts</a></li>
        <li class="mt-5 cursor-pointer border-l-2 border-transparent px-2 py-2 font-semibold transition hover:border-l-blue-700 hover:text-blue-700"><a href="./user_account.php">User Account</a></li>
        <li class="mt-5 cursor-pointer border-l-2 border-transparent px-2 py-2 font-semibold transition hover:border-l-blue-700 hover:text-blue-700">
          <a href="./profile_admin.php">Profile</a>
        </li>
        <li class="mt-5 cursor-pointer border-l-2 border-transparent px-2 py-2 font-semibold transition hover:border-l-blue-700 hover:text-blue-700"><a href="./add_product.php">Products</a></li>
         <li class="mt-5 cursor-pointer border-l-2 border-transparent px-2 py-2 font-semibold transition hover:border-l-blue-700 hover:text-blue-700"><a href="./orders.php">Orders</a></li>
         <li class="mt-5 cursor-pointer border-l-2 border-transparent px-2 py-2 font-semibold transition hover:border-l-blue-700 hover:text-blue-700"><a href="./message.php">Messages</a></li>

        
      </ul>
    </div>

    <div class="col-span-8 overflow-hidden rounded-xl  sm:px-8 sm:shadow">
      <div class="pt-4">
        <h1 class="py-2 text-2xl font-semibold">Account settings</h1>
      </div>
      <hr class="mt-4 mb-8" />

      <!-- Dynamic Email Address -->
      <p class="py-2 text-xl font-semibold">Email Address</p>
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <p class="text-gray-600">
          Your email address is <strong><?= htmlspecialchars($user_email) ?></strong>
        </p>
      </div>

      <hr class="mt-4 mb-8" />

      <!-- Password Fields -->
      <p class="py-2 text-xl font-semibold">Password</p>

      <?php if (!empty($_GET['error'])) : ?>
      <p class="text-red-500 mb-2"><?= htmlspecialchars($_GET['error']) ?></p>
      <?php endif; ?>

      <?php if (!empty($_GET['success'])) : ?>
      <p class="text-green-500 mb-2"><?= htmlspecialchars($_GET['success']) ?></p>
      <?php endif; ?>

      <form method="POST" action="setting_admin.php">
        <div class="flex items-center">
          <div class="flex flex-col space-y-2 sm:flex-row sm:space-y-0 sm:space-x-3">
            <label>
              <span class="text-sm text-gray-500">Current Password</span>
              <div class="relative flex overflow-hidden rounded-md border-2 transition focus-within:border-blue-600">
                <input
                  type="password"
                  name="current_password"
                  autocomplete="current-password"
                  class="w-full border-gray-300 bg-white py-2 px-4 text-base text-gray-700"
                  placeholder="***********"
                  required
                />
              </div>
            </label>
            <label>
              <span class="text-sm text-gray-500">New Password</span>
              <div class="relative flex overflow-hidden rounded-md border-2 transition focus-within:border-blue-600">
                <input
                  type="password"
                  name="new_password"
                  autocomplete="new-password"
                  class="w-full border-gray-300 bg-white py-2 px-4 text-base text-gray-700"
                  placeholder="***********"
                  required
                />
              </div>
            </label>
          </div>

          <!-- Remove or comment this SVG if not used for functionality -->
          <!--
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="mt-5 ml-2 h-6 w-6 cursor-pointer text-gray-600"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M3 3l3.59 3.59M21 21"
            />
          </svg>
          -->
        </div>

        <button
          type="submit"
          class="mt-4 rounded-lg mb-5 bg-pink-400 px-4 py-2 text-white hover:bg-pink-600"
        >
          Save Password
        </button>
      </form>

    </div>
  </div>
</div>

</body>
</html>

