<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/server/connection.php';

// Handle delete product
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $delete_stmt = mysqli_prepare($conn, "DELETE FROM products WHERE id = ?");
    mysqli_stmt_bind_param($delete_stmt, 'i', $id);
    mysqli_stmt_execute($delete_stmt);
    mysqli_stmt_close($delete_stmt);
    header("Location: " . $_SERVER['PHP_SELF'] . "?deleted=1");
    exit;
}

// Load product for editing
$edit_product = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $edit_result = mysqli_query($conn, "SELECT * FROM products WHERE id = $edit_id");
    if ($edit_result && mysqli_num_rows($edit_result) === 1) {
        $edit_product = mysqli_fetch_assoc($edit_result);
    }
}

// Detect add or edit mode
$showAddForm = isset($_GET['add']) && $_GET['add'] == 1;
$showEditForm = isset($_GET['edit']) && is_numeric($_GET['edit']);

// Handle product creation
if ($_SERVER["REQUEST_METHOD"] === "POST" && !isset($_GET['edit'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description1 = $_POST['description1'];
    $description2 = $_POST['description2'];
    $type = $_POST['type'];
    $stock = intval($_POST['stock'] ?? 0);

    $image_url = '';

    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image_file']['tmp_name'];
        $fileName = $_FILES['image_file']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedfileExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileExtension, $allowedfileExtensions)) {
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $uploadFileDir = __DIR__ . '/uploads/';
            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $image_url = 'uploads/' . $newFileName;
            } else {
                echo "<p class='text-red-600 font-semibold'>Error moving uploaded file.</p>";
                exit;
            }
        } else {
            echo "<p class='text-red-600 font-semibold'>Invalid file type.</p>";
            exit;
        }
    } elseif (!empty($_POST['image_url'])) {
        $image_url = trim($_POST['image_url']);
        if (!filter_var($image_url, FILTER_VALIDATE_URL) || !preg_match('/\.(jpg|jpeg|png|gif)$/i', $image_url)) {
            echo "<p class='text-red-600 font-semibold'>Invalid image URL format or extension.</p>";
            exit;
        }
    } else {
        echo "<p class='text-red-600 font-semibold'>Please upload an image or provide a URL.</p>";
        exit;
    }

    $sql = "INSERT INTO products (name, price, description1, description2, type, image_url, stock)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'sdssssi', $name, $price, $description1, $description2, $type, $image_url, $stock);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
        exit;
    } else {
        echo "<p class='text-red-600 font-semibold'>Error: " . mysqli_error($conn) . "</p>";
    }
    mysqli_stmt_close($stmt);
}

// Handle product update
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $price = $_POST['price'];
    $type = $_POST['type'];
    $stock = intval($_POST['stock']);

    $update_stmt = $conn->prepare("UPDATE products SET price = ?, type = ?, stock = ? WHERE id = ?");
    $update_stmt->bind_param('dsii', $price, $type, $stock, $id);

    if ($update_stmt->execute()) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?updated=1");
        exit;
    } else {
        echo "<p class='text-red-600 font-semibold'>Error updating product: " . $conn->error . "</p>";
    }
    $update_stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Product Settings</title>
    <link rel="stylesheet" href="./css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600;700&display=swap" rel="stylesheet" />
    <style>* { font-family: 'Source Sans Pro'; }</style>
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
           class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </a>
    </div>

    <div class="grid grid-cols-8 pt-10 sm:grid-cols-10">
        <!-- Sidebar code (unchanged) -->
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
                <li class="mt-5 cursor-pointer border-l-2 border-transparent px-2 py-2 font-semibold transition hover:border-l-blue-700 hover:text-blue-700"><a href="./profile_admin.php">Profile</a></li>
                <li class="mt-5 cursor-pointer border-l-2 border-l-blue-700 px-2 py-2 font-semibold text-blue-700 transition hover:border-l-blue-700 hover:text-blue-700"><a href="./add_product.php">Products</a></li>
                <li class="mt-5 cursor-pointer border-l-2 border-transparent px-2 py-2 font-semibold transition hover:border-l-blue-700 hover:text-blue-700"><a href="./orders.php">Orders</a></li>
                <li class="mt-5 cursor-pointer border-l-2 border-transparent px-2 py-2 font-semibold transition hover:border-l-blue-700 hover:text-blue-700"><a href="./message.php">Messages</a></li>
            </ul>
        </div>

        <div class="col-span-8 overflow-hidden rounded-xl  sm:shadow mb-8">
            <div class="p-6 border-b border-gray-200 flex justify-between">
                <div>
                    <h2 class="text-xl font-bold text-pink-400">Products</h2>
                    <p class="text-gray-500 mt-1">Manage your product listings below.</p>
                </div>
                <?php if (!$showAddForm && !$showEditForm): ?>
                    <a href="?add=1" class="bg-pink-400 hover:bg-pink-700 text-white font-medium px-4 py-2 rounded-lg shadow-sm">Add Product</a>
                <?php endif; ?>
            </div>

            <?php if (!$showAddForm && !$showEditForm): ?>
                <?php
                    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
                    $search_sql = '';
                    if (!empty($search)) {
                        $search = mysqli_real_escape_string($conn, $search);
                        $search_sql = "AND (name LIKE '%$search%' OR description1 LIKE '%$search%' OR type LIKE '%$search%')";
                    }

                    $limit = 7;
                    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
                    $offset = ($page - 1) * $limit;

                    $count_query = "SELECT COUNT(*) AS total FROM products WHERE type IN ('face', 'body', 'hair') $search_sql";
                    $count_result = mysqli_query($conn, $count_query);
                    $total_products = mysqli_fetch_assoc($count_result)['total'];
                    $total_pages = ceil($total_products / $limit);

                    $query = "SELECT * FROM products WHERE type IN ('face', 'body', 'hair') $search_sql LIMIT $limit OFFSET $offset";
                    $result = mysqli_query($conn, $query);
                ?>

                <form method="GET" class="p-4">
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search product..." class="w-1/2 p-2 border rounded" />
                    <button type="submit" class="ml-2 px-4 py-2 bg-pink-400 text-white rounded hover:bg-pink-700">Search</button>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y ">
                        <thead class="">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class=" divide-y ">
                            <?php if (mysqli_num_rows($result) === 0): ?>
                                <tr><td colspan="5" class="text-center text-red-500 py-4">No products found.</td></tr>
                            <?php endif;
                            while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap flex items-center gap-3">
                                        <img src="<?= htmlspecialchars($row['image_url']) ?>" class="w-10 h-10 rounded object-cover" alt="">
                                        <div>
                                            <div class="text-sm font-medium"><?= htmlspecialchars($row['name']) ?></div>
                                            <div class="text-xs text-gray-500"><?= htmlspecialchars($row['description1']) ?></div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm"><?= htmlspecialchars($row['type']) ?></td>
                                    <td class="px-6 py-4 text-sm">$<?= number_format($row['price'], 2) ?></td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="px-2 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            <?= intval($row['stock'] ?? 0) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium">
                                        <a href="?edit=<?= $row['id'] ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                        <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this product?');" class="text-red-600 hover:text-red-900">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($total_pages > 1): ?>
                    <div class="px-6 py-4 flex justify-center space-x-2">
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"
                               class="px-3 py-1 border <?= $i == $page ? 'bg-blue-600 text-white' : 'bg-white text-gray-700' ?> rounded shadow hover:bg-blue-700 hover:text-white">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Add/Edit Form (unchanged) -->
            <?php if ($showAddForm || $showEditForm): ?>
            <div class="p-6 border-t border-gray-200">
                <h2 class="text-xl text-pink-400 font-bold mb-4"><?= $edit_product ? "Edit Product" : "Add New Product" ?></h2>
                <form action="?<?= $edit_product ? "edit=" . $edit_product['id'] : "" ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
                    <?php if (!$edit_product): ?>
                        <input type="text" name="name" required placeholder="Product Name" class="w-full p-2 border rounded" />
                        <textarea name="description1" required placeholder="Description 1" class="w-full p-2 border rounded"></textarea>
                        <textarea name="description2" placeholder="Description 2" class="w-full p-2 border rounded"></textarea>
                        <input type="file" name="image_file" accept="image/*" class="w-full p-2 border rounded" />
                        <!-- <input type="url" name="image_url" placeholder="Or paste image URL" class="w-full p-2 border rounded" /> -->
                    <?php else: ?>
                        <div class="text-gray-700 font-semibold">Product Name: <?= htmlspecialchars($edit_product['name']) ?></div>
                        <div class="text-gray-500 mb-2">Description 1: <?= htmlspecialchars($edit_product['description1']) ?></div>
                        <div class="text-gray-500 mb-4">Description 2: <?= htmlspecialchars($edit_product['description2']) ?></div>
                        <img src="<?= htmlspecialchars($edit_product['image_url']) ?>" alt="Product Image" class="w-32 h-32 object-cover rounded mb-4" />
                    <?php endif; ?>

                    <input type="number" name="price" step="0.01" required placeholder="Price"
                           value="<?= $edit_product ? htmlspecialchars($edit_product['price']) : "" ?>" class="w-full p-2 border rounded" />

                    <select name="type" required class="w-full p-2 border rounded">
                        <option value="">Select Type</option>
                        <option value="face" <?= $edit_product && $edit_product['type'] === 'face' ? 'selected' : '' ?>>Face</option>
                        <option value="body" <?= $edit_product && $edit_product['type'] === 'body' ? 'selected' : '' ?>>Body</option>
                        <option value="hair" <?= $edit_product && $edit_product['type'] === 'hair' ? 'selected' : '' ?>>Hair</option>
                    </select>

                    <input type="number" name="stock" min="0" required placeholder="Stock"
                           value="<?= $edit_product ? htmlspecialchars($edit_product['stock']) : "" ?>" class="w-full p-2 border rounded" />

                    <button type="submit" class="px-4 py-2 bg-pink-400 text-white rounded hover:bg-pink-700">
                        <?= $edit_product ? "Update Product" : "Create Product" ?>
                    </button>
                    <a href="<?= $_SERVER['PHP_SELF'] ?>" class="ml-3 text-gray-600 hover:underline">Cancel</a>
                </form>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
