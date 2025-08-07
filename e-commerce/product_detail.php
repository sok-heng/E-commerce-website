<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

include './server/connection.php';
include 'functions.php';

// --- ADMIN DELETE REVIEW HANDLER ---
if (isset($_GET['delete_review'])) {
    if (!isset($_SESSION['user_email'])) {
        die('You must be logged in as admin to delete reviews.');
    }
    $reviewId = intval($_GET['delete_review']);

    // Check if current user is admin via user_role
    $userEmail = $_SESSION['user_email'];
    $stmtAdmin = $conn->prepare("SELECT user_role FROM users WHERE user_email = ?");
    $stmtAdmin->bind_param("s", $userEmail);
    $stmtAdmin->execute();
    $resAdmin = $stmtAdmin->get_result();

    if ($resAdmin->num_rows === 0) {
        die('User not found.');
    }
    $userAdmin = $resAdmin->fetch_assoc();

    if ($userAdmin['user_role'] !== 'admin') {
        die('You do not have permission to delete reviews.');
    }

    // Delete review
    $stmtDelete = $conn->prepare("DELETE FROM reviews WHERE id = ?");
    $stmtDelete->bind_param("i", $reviewId);
    if (!$stmtDelete->execute()) {
        die('Failed to delete review: ' . $stmtDelete->error);
    }

    // Redirect back to product page without query parameter
    $redirectUrl = 'product_detail.php?id=' . (isset($_GET['id']) ? intval($_GET['id']) : '') . '&deleted=1';
    header("Location: $redirectUrl");
    exit();
}

// Validate product id
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid product ID');
}
$id = (int)$_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    if (!isset($_SESSION['user_email'])) {
        die("You must be logged in to submit a review.");
    }

    $product_id = $id;
    $title = htmlspecialchars(trim($_POST['title']));
    $description = htmlspecialchars(trim($_POST['description']));
    $rating = intval($_POST['rating']);
    $imagePath = null;

    if ($rating < 1 || $rating > 5) {
        $rating = 0;
    }

    $userEmail = $_SESSION['user_email'];
    $stmtUser = $conn->prepare("SELECT user_id FROM users WHERE user_email = ?");
    $stmtUser->bind_param("s", $userEmail);
    $stmtUser->execute();
    $resultUser = $stmtUser->get_result();

    if ($resultUser->num_rows === 0) {
        die("User not found.");
    }
    $user = $resultUser->fetch_assoc();
    $userId = $user['user_id'];

    if (!empty($_FILES['review_image']['name'])) {
        $targetDir = "uploads/reviews/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        $fileName = uniqid() . "_" . basename($_FILES["review_image"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        if (move_uploaded_file($_FILES["review_image"]["tmp_name"], $targetFilePath)) {
            $imagePath = $targetFilePath;
        }
    }

    $stmtInsert = $conn->prepare("INSERT INTO reviews (product_id, user_id, title, description, rating, image_path) VALUES (?, ?, ?, ?, ?, ?)");
    $stmtInsert->bind_param("iissis", $product_id, $userId, $title, $description, $rating, $imagePath);

    if (!$stmtInsert->execute()) {
        die("Error inserting review: " . $stmtInsert->error);
    }

    header("Location: product_detail.php?id=$product_id&success=1");
    exit();
}

// Get product data
$query = "SELECT * FROM products WHERE id = $id LIMIT 1";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) === 0) {
    die('Product not found');
}
$product = mysqli_fetch_assoc($result);

// Get average rating and reviews count
$stmt = $conn->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as reviews_count FROM reviews WHERE product_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$ratingData = $res->fetch_assoc();
$avgRating = floatval($ratingData['avg_rating'] ?? 0);
$reviewsCount = intval($ratingData['reviews_count'] ?? 0);

// Get reviews joined with users for usernames
$stmt = $conn->prepare("
    SELECT r.*, u.user_name, u.user_role 
    FROM reviews r
    JOIN users u ON r.user_id = u.user_id
    WHERE r.product_id = ?
    ORDER BY r.created_at DESC
");
$stmt->bind_param("i", $id);
$stmt->execute();
$reviewsResult = $stmt->get_result();

// Get current user role for display logic
$currentUserIsAdmin = false;
if (isset($_SESSION['user_email'])) {
    $stmtRoleCheck = $conn->prepare("SELECT user_role FROM users WHERE user_email = ?");
    $stmtRoleCheck->bind_param("s", $_SESSION['user_email']);
    $stmtRoleCheck->execute();
    $resRoleCheck = $stmtRoleCheck->get_result();
    if ($resRoleCheck->num_rows > 0) {
        $userRoleCheck = $resRoleCheck->fetch_assoc();
        $currentUserIsAdmin = ($userRoleCheck['user_role'] === 'admin');
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= htmlspecialchars($product['name']) ?> - Product Detail</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/style.css" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
      integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
</head>
<body>

<?php include('./navbar.php'); ?>

<div class="area">
    <ul class="circles">
        <li></li><li></li><li></li><li></li><li></li>
        <li></li><li></li><li></li><li></li><li></li>
    </ul>
</div>

<section class="md:py-16 antialiased">
  <div class="max-w-screen-xl px-4 mx-auto 2xl:px-0">
    <div class="lg:grid lg:grid-cols-2 lg:gap-8 xl:gap-16">
      
      <!-- Product Image -->
      <div class="shrink-0 max-w-md lg:max-w-lg mx-auto">
        <img class="w-full" src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" />
      </div>

      <!-- Product Info -->
      <div class="mt-6 sm:mt-8 lg:mt-0">
        <!-- Product Title & Close -->
        <div class="flex justify-between items-center">   
          <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl">
            <?= htmlspecialchars($product['name']) ?>
          </h1>
          <button type="button"
            onclick="history.back()"
            class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
            <span class="sr-only">Close menu</span>
            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <!-- Price & Rating -->
        <div class="mt-4 sm:items-center sm:gap-4 sm:flex">
          <p class="text-2xl font-extrabold text-gray-900 sm:text-3xl">
            $<?= number_format($product['price'], 2) ?>
          </p>
          <div class="flex items-center gap-2 mt-2 sm:mt-0">
            <div class="flex items-center gap-1">
              <?= renderStars($avgRating) ?>
            </div>
            <p class="text-sm font-medium text-gray-500">(<?= number_format($avgRating, 2) ?>)</p>
            <a href="#reviews" class="text-sm font-medium text-gray-900 underline hover:no-underline">
              <?= $reviewsCount ?> Reviews
            </a>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 sm:mt-8 flex flex-col sm:flex-row sm:items-center sm:gap-4 gap-3">
          <a href="#"
            class="w-full sm:w-auto flex items-center justify-center py-2.5 px-5 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-100">
            Add to favorites
          </a>

          <a href="addtocart.php?add=<?= $product['id'] ?>"
            class="w-full sm:w-auto inline-block text-center py-2.5 px-5 text-sm font-medium text-white bg-pink-600 rounded-lg hover:bg-pink-700">
            Add to Cart
          </a>
        </div>

        <hr class="my-6 md:my-8 border-gray-200" />

        <!-- Descriptions -->
        <p class="mb-6 text-gray-500">
          <?= nl2br(htmlspecialchars($product['description1'])) ?>
        </p>
        <p class="text-gray-500">
          <?= nl2br(htmlspecialchars($product['description2'])) ?>
        </p>
      </div>
    </div>
  </div>
</section>


      <!-- card section  -->
   <section class=" pb-5">
   
    <section class=" mx-5 ">
       
 <div class="px-4 mb-4 py-20" data-aos="fade-up" data-aos-delay="200" data-aos-duration="1200">
  <h1 class="font-skin-care text-3xl sm:text-4xl md:text-5xl lg:text-5xl text-pink-500 font-semibold tracking-wide">
    <span class="text-black">Best</span> Selling Product
  </h1>
</div>


       
       <!-- card container -->
      

  <div class="">
  <div class="w-full">
    <div class="my-8">
      <div id="scrollContainer" class="flex flex-no-wrap overflow-x-scroll scrolling-touch items-start mb-8">
        <?php
        include('./server/connection.php'); // Make sure this is placed correctly at the top of your file

        $query = "SELECT * FROM products WHERE type IN ('face', 'body', 'hair') ORDER BY RAND()";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) === 0) {
          echo "<p class='text-red-500 col-span-full'>No products found for selected types.</p>";
        }

        while ($row = mysqli_fetch_assoc($result)) :
        ?>
        
        <!-- card -->
        <a href="" >
          <div class="flex-none w-[89vw] sm:w-[50vw] md:w-[30vw] lg:w-[23.5vw] mr-4 border rounded-lg" data-aos-duration="1300" data-aos-delay="200" data-aos="zoom-in-up">
            <div class="w-full border border-pink-300 rounded-lg shadow-md p-4">
              <!-- Discount + Wishlist -->
              <div class="relative">
                <?php if ($row['stock'] > 0): ?>
                <span class="absolute bg-pink-500 text-white text-xs font-semibold px-2 py-1 rounded-md">
                  Available
                </span>
                <?php else: ?>
                <span class="absolute bg-red-500 text-white text-xs font-semibold px-2 py-1 rounded-md">
                  Out of Stock
                </span>
                <?php endif; ?>

                <!-- Wishlist Icon -->
                <button class="absolute top-2 right-2 w-8 h-8 bg-white rounded-full shadow flex items-center justify-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z" />
                  </svg>
                </button>

                <!-- Product Image -->
                <div class="aspect-[4/3] w-full " >
                  <a href="product_detail.php?id=<?= urlencode($row['id']) ?>">
                    <img  src="<?= htmlspecialchars($row['image_url']) ?>" alt="Product Image"
                      class="object-cover w-full h-full rounded-md scale-a-lil"  />
                  </a>
                </div>
              </div>

              <!-- Product Details -->
              <div class="mt-4 space-y-2">
                <div>
                  <h3 class="text-gray-800 font-medium text-base leading-snug">
                    <?= htmlspecialchars($row['name']) ?>
                  </h3>
                  <p class="line-clamp-2 text-gray-400">
                    <?= htmlspecialchars($row['description1']) ?>
                  </p>
                </div>

                <p class="text-xs"> FOR:<span class="uppercase text-pink-600 text-xs font-medium">
                    <?= strtoupper($row['type']) ?></span></p>

                <!-- Ratings -->
                <div class="flex space-x-1 text-orange-500 text-sm">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor"
                    viewBox="0 0 20 20">
                    <path d="..." />
                  </svg>
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor"
                    viewBox="0 0 20 20">
                    <path d="..." />
                  </svg>
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor"
                    viewBox="0 0 20 20">
                    <path d="..." />
                  </svg>
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor"
                    viewBox="0 0 20 20">
                    <path d="..." />
                  </svg>
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-300" fill="currentColor"
                    viewBox="0 0 20 20">
                    <path d="..." />
                  </svg>
                </div>

                <!-- Price + Cart -->
                <div class="flex items-center justify-between mt-2">
                  <div class="flex items-baseline space-x-2">
                    <span class="text-pink-600 text-xl font-semibold">
                      $<?= number_format($row['price'], 2) ?></span>
                    <span class="text-gray-400 text-sm line-through">$1500.00</span>
                  </div>

                 <!-- button add to cart -->
                  <a href="addtocart_stay.php?add=<?= $row['id'] ?>&redirect=<?= urlencode($_SERVER['REQUEST_URI']) ?>"
                    class="bg-transparent w-10 h-10 rounded-full flex items-center justify-center shadow text-pink-500 border border-pink-500 hover:bg-pink-500 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                      viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                      stroke-linecap="round" stroke-linejoin="round">
                      <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                      <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                      <path d="M17 17h-11v-14h-2" />
                      <path d="M6 5l14 1l-1 7h-13" />
                    </svg>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </a>
        <?php endwhile; ?>
      </div>
    </div>
  </div>
</div>

    </div>
</section>
   </section>

<!-- Review Section -->
<section id="reviews" class="py-8 antialiased md:py-16">
  <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div class="flex flex-col sm:flex-row sm:items-center gap-2">
        <h2 class="text-2xl font-semibold text-black">Reviews</h2>
        <div class="flex items-center gap-2 sm:ml-4">
          <div class="flex items-center gap-0.5">
            <?= renderStars($avgRating) ?>
          </div>
          <p class="text-sm font-medium text-black">(<?= number_format($avgRating, 2) ?>)</p>
          <span class="text-sm font-medium text-black underline hover:no-underline"><?= $reviewsCount ?> Reviews</span>
        </div>
      </div>

      <button onclick="document.getElementById('review-form').classList.toggle('hidden')" class="px-4 py-2 bg-pink-400 text-white rounded hover:bg-pink-700 w-full sm:w-auto">
        Write a Review
      </button>
    </div>

    <div id="review-form" class="mt-6 hidden border border-gray-200 p-6 rounded-lg shadow-sm">
      <form action="" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="submit_review" value="1" />

        <div class="mb-4">
          <label for="title" class="block text-sm font-medium text-black">Title</label>
          <input type="text" name="title" id="title" required class="mt-1 block w-full rounded-md border border-gray-300 p-2" />
        </div>

        <div class="mb-4">
          <label for="description" class="block text-sm font-medium text-black">Description</label>
          <textarea name="description" id="description" required rows="4" class="mt-1 block w-full rounded-md border border-gray-300 p-2"></textarea>
        </div>

        <div class="mb-4">
          <label for="rating" class="block text-sm font-medium text-black">Rating</label>
          <select name="rating" id="rating" required class="mt-1 block w-full rounded-md border border-gray-300 p-2">
            <option value="">Select Rating</option>
            <option value="5">★★★★★ (5)</option>
            <option value="4">★★★★☆ (4)</option>
            <option value="3">★★★☆☆ (3)</option>
            <option value="2">★★☆☆☆ (2)</option>
            <option value="1">★☆☆☆☆ (1)</option>
          </select>
        </div>

        <div class="mb-4">
          <label for="review_image" class="block text-sm font-medium text-black">Upload Image (optional)</label>
          <input type="file" name="review_image" id="review_image" accept="image/*" class="mt-1 block w-full" />
        </div>

        <div>
          <button type="submit" class="px-4 py-2 bg-pink-400 text-white rounded hover:bg-pink-700">Submit Review</button>
        </div>
      </form>
    </div>

    <div class="mt-6 divide-y divide-gray-200">
      <?php while ($review = $reviewsResult->fetch_assoc()): ?>
        <div class="pb-6 pt-6 flex flex-col sm:flex-row gap-4 sm:items-start justify-between">
          <div class="sm:w-48 md:w-72 space-y-2 shrink-0">
            <div class="flex items-center gap-0.5"><?= renderStars($review['rating']) ?></div>
            <div class="space-y-0.5">
              <p class="text-base font-semibold text-black"><?= htmlspecialchars($review['user_name']) ?></p>
              <p class="text-sm text-black"><?= date("F j, Y, g:i a", strtotime($review['created_at'])) ?></p>
            </div>
          </div>

          <div class="flex-1 flex flex-col md:flex-row gap-4 items-start">
            <div class="flex-1 space-y-4">
              <p class="text-base font-semibold text-black"><?= htmlspecialchars($review['title']) ?></p>
              <p class="text-base text-black"><?= nl2br(htmlspecialchars($review['description'])) ?></p>
            </div>

            <?php if (!empty($review['image_path'])): ?>
              <div class="w-24 h-24 shrink-0">
                <img src="<?= htmlspecialchars($review['image_path']) ?>" alt="Review Image" class="w-full h-full object-cover rounded-md shadow" />
              </div>
            <?php endif; ?>

            <?php if ($currentUserIsAdmin): ?>
              <div>
                <a href="?id=<?= $id ?>&delete_review=<?= $review['id'] ?>" 
                   onclick="return confirm('Are you sure you want to delete this review?');"
                   class="text-red-600 hover:text-red-800 font-semibold text-sm">
                  Delete
                </a>
              </div>
            <?php endif; ?>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
</section>

<?php include('./footer.php'); ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init();
</script>
</body>
</html>
