<?php
session_start();
include('./server/connection.php');

?>
<!DOCTYPE html>
<html lang="en"  >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
          <style>



  .font-skin-care {
font-family: 'Playfair Display', serif;
  }
html {
  scroll-behavior: smooth;
}
  </style>
</head>
<body>
  <?php
    include('./navbar.php');
?>
    <?php if (isset($_GET['error']) && $_GET['error'] === 'out_of_stock'): ?>
<div id="popup-overlay" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
  <div class="bg-white w-80 rounded-lg shadow-lg text-center p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-2">Error</h2>
    <p class="text-gray-600 mb-4">This item is out of stock. Please try again later.</p>
    <button onclick="dismissPopup()"
      class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
      Dismiss
    </button>
  </div>
</div>

<script>
function dismissPopup() {
  const popup = document.getElementById('popup-overlay');
  if (popup) popup.remove();

  // Clean up the URL
  const url = new URL(window.location);
  url.searchParams.delete('error');
  window.history.replaceState({}, document.title, url);
}
</script>
<?php endif; ?>
 


</div>
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
<section class="-z-10 ">
  <div class="relative -z-10 bg-gradient-to-r from-purple-600 to-blue-600 h-[400px] md:h-[500px] lg:h-[600px] text-white overflow-hidden">
  
  
    <div class="relative -z-10 flex flex-col justify-center items-center h-full text-center px-4">
      <h1 class="text-2xl text-pink-300 sm:text-3xl md:text-4xl lg:text-5xl font-bold leading-tight mb-4 text-black drop-shadow-[0_4px_4px_rgba(251,207,232,1)] typing-animation overflow-hidden whitespace-nowrap border-r-4 border-pink-200">
        Welcome to Our Awesome Website
      </h1>
      <p class="text-sm sm:text-base md:text-lg lg:text-xl text-black mb-8 drop-shadow-[0_2px_2px_rgba(251,207,232,1)] typing-animation1">
        Discover amazing features <span class="text-pink-300">and services that await you.</span>
      </p>
    
    </div>
   
  </div>
</section>
<section>

</section>
   <!-- card section  -->
   <section id="faceSection" class=" pb-5">
   
    <section class=" mx-5 ">
       
 <div class="px-4 mb-4 py-20" data-aos="fade-up" data-aos-delay="100" data-aos-duration="800">
  <h1 class="font-skin-care text-3xl sm:text-4xl md:text-5xl lg:text-5xl text-pink-400 font-semibold tracking-wide">
    Products <span class="text-black">For Face</span>
  </h1>
</div>


       
       <!-- card container -->
      

  <div class="">
  <div class="w-full">
    <div class="my-8">
      <div id="scrollContainer" class="flex flex-no-wrap overflow-x-scroll scrolling-touch items-start mb-8">
        <?php
        include('./server/connection.php'); // Make sure this is placed correctly at the top of your file

        $query = "SELECT * FROM products WHERE type IN ('face') ORDER BY RAND()";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) === 0) {
          echo "<p class='text-red-500 col-span-full'>No products found for selected types.</p>";
        }

        while ($row = mysqli_fetch_assoc($result)) :
        ?>
        
        <!-- card -->
        <a href="" >
          <div class="flex-none w-[89vw] sm:w-[50vw] md:w-[30vw] lg:w-[23.5vw] mr-4 border rounded-lg" data-aos-duration="800" data-aos-delay="100" data-aos="zoom-in-up">
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

                  <!-- ✅ Fixed Add to Cart Button -->
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
      <!-- card section  -->
   <section id="bodySection" class=" pb-5">
   
    <section class=" mx-5 ">
       
 <div class="px-4 mb-4 py-20" data-aos="fade-up" data-aos-delay="100" data-aos-duration="800">
  <h1 class="font-skin-care text-3xl sm:text-4xl md:text-5xl lg:text-5xl text-pink-500 font-semibold tracking-wide">
    <span class="text-black">Products</span> For Body
  </h1>
</div>


       
       <!-- card container -->
      

  <div class="">
  <div class="w-full">
    <div class="my-8">
      <div id="scrollContainer" class="flex flex-no-wrap overflow-x-scroll scrolling-touch items-start mb-8">
        <?php
        include('./server/connection.php'); // Make sure this is placed correctly at the top of your file

        $query = "SELECT * FROM products WHERE type IN ('body') ORDER BY RAND()";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) === 0) {
          echo "<p class='text-red-500 col-span-full'>No products found for selected types.</p>";
        }

        while ($row = mysqli_fetch_assoc($result)) :
        ?>
        
        <!-- card -->
        <a href="" >
          <div class="flex-none w-[89vw] sm:w-[50vw] md:w-[30vw] lg:w-[23.5vw] mr-4 border rounded-lg" data-aos-duration="800" data-aos-delay="100" data-aos="zoom-in-up">
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

                  <!-- ✅ Fixed Add to Cart Button -->
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
      <!-- card section  -->
   <section id="hairSection" class=" pb-5">
   
    <section class=" mx-5 ">
       
 <div class="px-4 mb-4 py-20" data-aos="fade-up" data-aos-delay="100" data-aos-duration="800">
  <h1 class="font-skin-care text-3xl sm:text-4xl md:text-5xl lg:text-5xl text-pink-500 font-semibold tracking-wide">
   Products <span class="text-black">For Hair</span>
  </h1>
</div>


       
       <!-- card container -->
      

  <div class="">
  <div class="w-full">
    <div class="my-8">
      <div id="scrollContainer" class="flex flex-no-wrap overflow-x-scroll scrolling-touch items-start mb-8">
        <?php
        include('./server/connection.php'); // Make sure this is placed correctly at the top of your file

        $query = "SELECT * FROM products WHERE type IN ( 'hair') ORDER BY RAND()";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) === 0) {
          echo "<p class='text-red-500 col-span-full'>No products found for selected types.</p>";
        }

        while ($row = mysqli_fetch_assoc($result)) :
        ?>
        
        <!-- card -->
        <a href="" >
          <div class="flex-none w-[89vw] sm:w-[50vw] md:w-[30vw] lg:w-[23.5vw] mr-4 border rounded-lg" data-aos-duration="800" data-aos-delay="100" data-aos="zoom-in-up">
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

                  <!-- ✅ Fixed Add to Cart Button -->
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
   <!-- footer -->
  <?php
       include('footer.php');
   ?>
 
 <script>
  window.addEventListener('load', () => {
    if (window.location.hash) {
      const el = document.querySelector(window.location.hash);
      if (el) {
        setTimeout(() => {
          el.scrollIntoView({ behavior: 'smooth' });
        }, 500); // delay in milliseconds (500ms = 0.5 seconds)
      }
    }
  });
</script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
     <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
  AOS.init();
</script>


</body>
<script src="https://unpkg.com/flowbite@1.6.5/dist/flowbite.min.js"></script>

</html>