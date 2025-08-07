<?php
// view_contact_messages.php

ini_set('display_errors', 1);
error_reporting(E_ALL);

include('./server/connection.php');

// Delete functionality
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    header("Location:message.php");
    exit();
}

// Fetch messages with user_name
$query = "SELECT c.*, u.user_name 
          FROM contact_messages c 
          LEFT JOIN users u ON c.user_id = u.user_id 
          ORDER BY c.submitted_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Contact Messages</title>
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
  <label for="select-1" class="flex w-full cursor-pointer select-none rounded-lg border p-2 px-3 text-sm text-gray-700 ring-blue-700 peer-checked:ring">
    Accounts
  </label>
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
         <li class="mt-5 cursor-pointer border-l-2 border-transparent px-2 py-2 font-semibold transition hover:border-l-blue-700 hover:text-blue-700"><a href="./orders.php">Orders</a></li>
         
         <li class="mt-5 cursor-pointer border-l-2 border-l-blue-700 px-2 py-2 font-semibold text-blue-700 transition hover:border-l-blue-700 hover:text-blue-700"><a href="./message.php">Messages</a></li>

        
      </ul>
    </div>

    <div class="col-span-8 overflow-hidden rounded-xl  sm:px-8 sm:shadow">
    <div class=" py-8">
  <div class="max-w-7xl mx-auto">
    <h1 class="text-4xl font-extrabold mb-8 text-center text-gray-900"><span class="text-pink-400">Contact</span> Messages</h1>

    <div class="grid grid-cols-1 sm:grid-cols-1 lg:grid-cols-2 gap-8">
      <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <div class=" shadow-md rounded-2xl p-6 border border-gray-200 hover:shadow-lg transition-shadow duration-300 relative flex flex-col">
          <div class="absolute top-4 right-4">
            <a href="?delete_id=<?php echo $row['id']; ?>" 
               onclick="return confirm('Are you sure you want to delete this message?');"
               class="text-red-600 hover:text-red-800 font-semibold text-sm">
              Delete
            </a>
          </div>
          
          <h2 class="text-2xl font-semibold text-gray-900 mb-3">
            <?php 
              echo $row['user_name'] 
                  ? htmlspecialchars($row['user_name']) 
                  : htmlspecialchars($row['name']); 
            ?>
          </h2>
          
          <div class="flex flex-col space-y-1 mb-4 text-gray-600">
            <p class="flex items-center gap-2 text-sm">
              <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 12H8m8 4H8m2-8h4M12 12v.01M12 16v.01"></path></svg>
              <span><?php echo htmlspecialchars($row['email']); ?></span>
            </p>
            <p class="flex items-center gap-2 text-sm">
              <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5h2l3 7-3 7H3m16-14h2l-3 7 3 7h-2"></path></svg>
              <span><?php echo htmlspecialchars($row['phone']); ?></span>
            </p>
          </div>
          <div>
             <p class="mb-2 font-semibold">Messages:</p>
          <p class="text-gray-700 whitespace-pre-line mb-6 flex-grow"><?php echo htmlspecialchars($row['message']); ?></p>
          
          </div>
          <p class="text-xs text-gray-400 text-right italic tracking-wide"><?php echo date('M d, Y H:i', strtotime($row['submitted_at'])); ?></p>
        </div>
      <?php } ?>
    </div>
  </div>
</div>
</div>
</div>
</body>
</html>
