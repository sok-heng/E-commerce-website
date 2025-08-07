<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/server/connection.php';

// 🔒 Admin-only access check
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: /FN/e-commerce/homepage.php");
    exit;
}

// Make sure logged-in user ID is available
$logged_in_user_id = $_SESSION['user_id'] ?? 0;

$message = "";

// Handle role update
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['user_id'], $_POST['new_role'], $_POST['current_role'])) {
    $user_id = intval($_POST['user_id']);
    $new_role = $_POST['new_role'];
    $current_role = $_POST['current_role'];

    if ($new_role === $current_role) {
        $_SESSION['flash_message'] = ['text' => "No changes made. Please select a different role.", 'type' => 'error'];
    } else {
        $update_stmt = mysqli_prepare($conn, "UPDATE users SET user_role = ? WHERE user_id = ?");
        mysqli_stmt_bind_param($update_stmt, 'si', $new_role, $user_id);
        mysqli_stmt_execute($update_stmt);
        mysqli_stmt_close($update_stmt);

        $_SESSION['flash_message'] = ['text' => "Role updated successfully!", 'type' => 'info'];
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Handle delete user
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $delete_stmt = mysqli_prepare($conn, "DELETE FROM users WHERE user_id = ?");
    mysqli_stmt_bind_param($delete_stmt, 'i', $id);
    mysqli_stmt_execute($delete_stmt);
    mysqli_stmt_close($delete_stmt);

    $_SESSION['flash_message'] = ['text' => "User deleted successfully!", 'type' => 'success'];
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Get and clear flash message for display
$flash = $_SESSION['flash_message'] ?? null;
if ($flash) {
    unset($_SESSION['flash_message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>User Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/flowbite@1.6.5/dist/flowbite.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="./css/style.css">
    <style>* { font-family: 'Source Sans Pro'; }</style>
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
            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
        
        <li class="mt-5 cursor-pointer border-l-2 border-transparent px-2 py-2 font-semibold transition hover:border-l-blue-700 hover:text-blue-700"><a href="setting_admin.php">Accounts</a></li>
        <li class="mt-5 cursor-pointer border-l-2 border-l-blue-700 px-2 py-2 font-semibold text-blue-700 transition hover:border-l-blue-700 hover:text-blue-700"><a href="./user_account.php">User Account</a></li>
        <li class="mt-5 cursor-pointer border-l-2 border-transparent px-2 py-2 font-semibold transition hover:border-l-blue-700 hover:text-blue-700">
          <a href="./profile_admin.php">Profile</a>
        </li>
        <li class="mt-5 cursor-pointer border-l-2 border-transparent px-2 py-2 font-semibold transition hover:border-l-blue-700 hover:text-blue-700"><a href="./add_product.php">Products</a></li>
         <li class="mt-5 cursor-pointer border-l-2 border-transparent px-2 py-2 font-semibold transition hover:border-l-blue-700 hover:text-blue-700"><a href="./orders.php">Orders</a></li>
         <li class="mt-5 cursor-pointer border-l-2 border-transparent px-2 py-2 font-semibold transition hover:border-l-blue-700 hover:text-blue-700"><a href="./message.php">Messages</a></li>

        
      </ul>
        </div>

        <div class="col-span-8 overflow-hidden rounded-xl sm:px-8 sm:shadow mb-8">
           
            <div class="mx-4 min-h-screen max-w-screen-xl sm:mx-8 xl:mx-auto pt-6">
                <h1 class="text-4xl font-semibold mb-6">Accounts</h1>

                <?php if ($flash): ?>
                    <div id="messageBox" class="flex items-center p-4 mb-4 text-sm rounded-lg
                        <?= $flash['type'] === 'error' ? 'text-red-700 bg-red-100 border border-red-400' : 
                            ($flash['type'] === 'info' ? 'text-blue-700 bg-blue-100 border border-blue-400' : 
                            'text-green-700 bg-green-100 border border-green-400') ?>" role="alert">
                        <svg class="w-5 h-5 inline mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="font-medium"><?= htmlspecialchars($flash['text']) ?></span>
                    </div>
                <?php endif; ?>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y ">
                        <thead class="">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Profile</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Option</th>
                            </tr>
                        </thead>
                        <tbody class=" divide-y ">
                            <?php
                            // Prepare and execute the query excluding current logged-in user
                            $query = "SELECT user_id, user_name, user_email, user_role, user_profile FROM users WHERE user_id != ? ORDER BY user_name ASC";
                            $stmt = mysqli_prepare($conn, $query);
                            mysqli_stmt_bind_param($stmt, "i", $logged_in_user_id);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);

                            if (mysqli_num_rows($result) === 0): ?>
                                <tr><td colspan="5" class="text-center text-red-500 py-4">No users found.</td></tr>
                            <?php else:
                                while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr class="">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php if (!empty($row['user_profile'])): ?>
                                                <img src="<?= htmlspecialchars($row['user_profile']) ?>" alt="Profile" class="w-10 h-10 rounded-full object-cover" />
                                            <?php else: ?>
                                                <div class="w-10 h-10 rounded-full  flex items-center justify-center text-gray-600">N/A</div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900"><?= htmlspecialchars($row['user_name']) ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-500"><?= htmlspecialchars($row['user_email']) ?></td>
                                        <form method="POST">
                                            <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>" />
                                            <input type="hidden" name="current_role" value="<?= htmlspecialchars($row['user_role']) ?>" />
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                <select name="new_role" class="border border-gray-300 rounded p-1 text-sm">
                                                    <option value="user" <?= $row['user_role'] === 'user' ? 'selected' : '' ?>>User</option>
                                                    <option value="admin" <?= $row['user_role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                                </select>
                                            </td>
                                            <td class="px-6 py-4 text-right text-sm font-medium">
                                                <button type="submit" class="bg-pink-400 hover:bg-pink-700 text-white px-4 py-1 rounded text-sm mr-2">Save</button>
                                                <a href="?delete=<?= $row['user_id'] ?>" onclick="return confirm('Are you sure you want to delete this user?');" class="text-red-600 hover:text-red-900">Delete</a>
                                            </td>
                                        </form>
                                    </tr>
                                <?php endwhile;
                            endif;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Auto-hide messages after 5 seconds -->
<script>
    setTimeout(() => {
        const msg = document.getElementById("messageBox");
        if (msg) {
            msg.classList.add("opacity-0", "transition-opacity", "duration-1000");
            setTimeout(() => msg.remove(), 1000);
        }
    }, 5000);
</script>

<script src="https://unpkg.com/flowbite@1.6.5/dist/flowbite.min.js"></script>
</body>
</html>
