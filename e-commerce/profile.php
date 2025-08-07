<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once __DIR__ . '/server/connection.php';

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// ✅ Validate session by user_id only
if (!isset($_SESSION['user_id'])) {
    die("Error: User not logged in.");
}
$user_id = $_SESSION['user_id'];
$message = "";

// ✅ Flash message
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

// ✅ Update profile logic
if (isset($_POST['save_profile'])) {
    // Fetch user role using user_id
    $sqlRole = "SELECT user_role FROM users WHERE user_id=?";
    $stmtRole = mysqli_prepare($conn, $sqlRole);
    mysqli_stmt_bind_param($stmtRole, "i", $user_id);
    mysqli_stmt_execute($stmtRole);
    $resultRole = mysqli_stmt_get_result($stmtRole);
    $userRoleRow = mysqli_fetch_assoc($resultRole);

    if (!$userRoleRow || $userRoleRow['user_role'] !== 'user') {
        die("❌ Only user role can access this page.");
    }

    // Get form data
    $name = trim($_POST['user_name']);
    $email = trim($_POST['user_email']);
    $phone = trim($_POST['user_phone']);
    $location = trim($_POST['user_location']);
    $profile_path = null;

    // Validate inputs
    if ($name === "" || $email === "" || $phone === "" || $location === "") {
        $_SESSION['message'] = "❌ All fields are required.";
        header("Location: profile.php");
        exit;
    }

    // ✅ Case 1: Upload file
    if (isset($_FILES['user_profile']) && $_FILES['user_profile']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['user_profile']['tmp_name'];
        $fileName = $_FILES['user_profile']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($fileExtension, $allowedExts)) {
            $uploadDir = __DIR__ . "/uploads/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
                chmod($uploadDir, 0777);
            }

            $newFileName = uniqid('profile_', true) . '.' . $fileExtension;
            $destPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $profile_path = 'uploads/' . $newFileName;
            } else {
                $_SESSION['message'] = "❌ Failed to move uploaded file.";
                header("Location: profile.php");
                exit;
            }
        } else {
            $_SESSION['message'] = "❌ Unsupported file extension.";
            header("Location: profile.php");
            exit;
        }
    }
    // ✅ Case 2: URL fallback
    elseif (!empty($_POST['user_profile_url'])) {
        $imageUrl = trim($_POST['user_profile_url']);
        $imgExt = strtolower(pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION));
        $validExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($imgExt, $validExts)) {
            $profile_path = $imageUrl;
        } else {
            $_SESSION['message'] = "❌ Invalid image URL extension.";
            header("Location: profile.php");
            exit;
        }
    }

    // ✅ Update query
    if ($profile_path !== null) {
        $sql = "UPDATE users SET user_name=?, user_email=?, user_phone=?, user_location=?, user_profile=? WHERE user_id=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssssi", $name, $email, $phone, $location, $profile_path, $user_id);
    } else {
        $sql = "UPDATE users SET user_name=?, user_email=?, user_phone=?, user_location=? WHERE user_id=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssi", $name, $email, $phone, $location, $user_id);
    }

    if ($stmt && mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = mysqli_stmt_affected_rows($stmt) > 0
            ? "✅ Profile updated successfully!"
            : "ℹ️ No changes were made.";
    } else {
        $_SESSION['message'] = "❌ Update failed: " . mysqli_error($conn);
    }

    header("Location: profile.php");
    exit;
}

// ✅ Load user data by ID
$sql = "SELECT * FROM users WHERE user_id=?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

// ✅ Block access if not found or wrong role
if (!$user) {
    die("❌ User not found.");
}
if ($user['user_role'] !== 'user') {
    header("Location: /FN/e-commerce/unauthorized.php");
    exit;
}

// ✅ Use placeholder if no image
$imgPath = !empty($user['user_profile']) ? $user['user_profile'] : 'https://via.placeholder.com/150';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Settings</title>
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
           class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
           aria-label="Close menu">
            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </a>
    </div>

    <div class="grid grid-cols-8 pt-10 sm:grid-cols-10">
        <div class="col-span-2 hidden sm:block">
           <ul>
        <li class="mt-5 cursor-pointer border-l-2 border-transparent px-2 py-2 font-semibold transition hover:border-l-blue-700 hover:text-blue-700"><a href="./setting.php">Accounts</a></li>
        <li class="mt-5 cursor-pointer border-l-2 border-transparent px-2 py-2 font-semibold transition hover:border-l-blue-700 hover:text-blue-700"><a href="./profile.php">Profile</a></li>
        <li class="mt-5 cursor-pointer border-l-2 border-transparent px-2 py-2 font-semibold transition hover:border-l-blue-700 hover:text-blue-700"><a href="./order_user.php">Your Order</a></li>
        
      </ul>
        </div>

        <div class="col-span-8 overflow-hidden rounded-xl  sm:px-8 sm:shadow mb-8">
            <?php if ($message): ?>
                <div class="mb-4 text-green-700 font-semibold bg-green-100 border border-green-300 p-4 rounded">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form class="space-y-4" method="POST" enctype="multipart/form-data" action="profile.php">
                <div class="flex flex-col md:flex-row justify-between mb-5 items-start pt-2">
                    <h2 class="mb-5 text-4xl font-bold "><span class="text-pink-400">Update</span> Profile</h2>
                    <div class="text-center">
                        <img src="<?= htmlspecialchars($imgPath) ?>"
                             alt="Profile Picture"
                             class="rounded-full w-32 h-32 mx-auto border-4 border-pink-500 mb-4 transition-transform duration-300 hover:scale-105 ring ring-gray-300">

                        <div class="mt-2">
                            <label for="user_profile" class="block text-sm font-medium text-gray-700 cursor-pointer">Upload image from PC</label>
                            <input type="file" id="user_profile" name="user_profile" accept="image/*"
                                   class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div class="mt-4">
                            <label for="user_profile_url" class="block text-sm font-medium text-gray-700">Or paste image URL</label>
                            <input type="url" id="user_profile_url" name="user_profile_url"
                                   class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="https://example.com/image.jpg">
                        </div>
                    </div>
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" id="name" name="user_name" value="<?= htmlspecialchars($user['user_name']) ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="user_email" value="<?= htmlspecialchars($user['user_email']) ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                    <input type="tel" id="phone" name="user_phone" value="<?= htmlspecialchars($user['user_phone']??'')?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>

                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                    <input type="text" id="location" name="user_location" value="<?= htmlspecialchars($user['user_location']??'') ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>

                <div class="flex justify-end space-x-4 pb-5">
                    <button type="reset" class="px-4 py-2 border border-pink-400 hover:text-white text-pink-400 rounded-lg hover:bg-pink-400">Cancel</button>
                    <button type="submit" name="save_profile"
                            class="px-4 py-2 bg-pink-400 text-white rounded-lg hover:bg-pink-700">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
