
<?php
session_start();
include('./server/connection.php');

?>

<!DOCTYPE html>
<html lang="en" class=" ">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
      <style>
  .font-skin-care {
font-family: 'Playfair Display', serif;
  }
  html {
  scroll-behavior: smooth;
}
</style>
</head>
<body >

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

    <!-- navbar menu -->


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
  <!-- introduction section -->
  <header>
<section class="">
	<div class=" text-white py-1">
		<div class="container mx-auto flex flex-col md:flex-row items-center my-12 md:my-24">
			<div class="flex flex-col w-full lg:w-1/3 justify-center items-start p-8">
				<h1 class="text-3xl md:text-5xl p-2 text-pink-300 tracking-loose typing-animation overflow-hidden " >BloomSkin</h1>
				<h2 class="text-3xl text-black md:text-5xl leading-relaxed md:leading-snug mb-2 typing-animation1 overflow-hidden">Space : The Timeless Infinity
				</h2>
				<p class="text-sm md:text-base text-black typing-animation1 overflow-hidden">Explore your favourite events and </p>
        <p class="text-sm md:text-base text-black mb-4 typing-animation1 overflow-hidden">register now to showcase your talent and win exciting prizes.</p>
         <p class=" typing-animation1 "> </p>
				<button id="shopNowBtn"
					class="bg-transparent btn hover:bg-pink-200 text-pink-200 hover:text-pink-300 rounded shadow hover:shadow-lg py-2 px-4 border border-pink-300 hover:border-transparent  transition">
					Explore Now</button>
			</div>
			<div class="p-8 mt-12 mb-6 md:mb-0 md:mt-0 ml-0 md:ml-12 lg:w-2/3  justify-center">
				<div class="h-48 flex flex-wrap content-center gap-8">
					<div>
						<img class="inline-block mt-28 hidden xl:block rounded-full  w-48  " src="https://www.miaqo.ch/cdn/shop/files/5946FBB9-5002-41B5-BF81-36399D200253.webp?v=1730640793" height="" ></div>
						<div>
             
							<img class="inline-block mt-24 md:mt-0 p-8 md:p-0 w-48 rounded-full"  src="https://www.miaqo.ch/cdn/shop/files/5946FBB9-5002-41B5-BF81-36399D200253.webp?v=1730640793"></div>
							<div>
								<img class="inline-block mt-28 hidden lg:block w-48 rounded-full" src="https://www.miaqo.ch/cdn/shop/files/5946FBB9-5002-41B5-BF81-36399D200253.webp?v=1730640793"></div>
              
               
							</div>
        
						</div>
					</div>
				</div>
</section>
  </header>
  <!-- body of home page -->
    <section class="pt-8">
  <div class="bg-gradient-to-tr from-pink-200 via-pink-200 to-white  px-4 py-12 gap-4  relative">
      <div class="flex items-center justify-evenly flex-wrap gap-y-3 gap-x-6 ">
        <div>
          <h6 class="text-base text-slate-900 ">Trusted by over 10,000+</h6>
          <p class="text-base text-slate-600  leading-relaxed mt-0.5">clients worldwide since 2018. </p>
        </div>
        <div class="flex gap-4">
          <div class="bg-pink-200 px-4 py-1.5 rounded-lg text-center">
            <span class="text-base font-semibold text-slate-900">24</span>
            <p class="text-xs text-slate-600 font-medium">Hours</p>
          </div>
          <div class="bg-pink-200 px-4 py-1.5 rounded-lg text-center">
            <span class="text-base font-semibold text-slate-900">36</span>
            <p class="text-xs text-slate-600 font-medium">Minutes</p>
          </div>
          <div class="bg-pink-200 px-4 py-1.5 rounded-lg text-center">
            <span class="text-base font-semibold text-slate-900">52</span>
            <p class="text-xs text-slate-600 font-medium">Seconds</p>
          </div>
        </div>
      </div>

      
    </div>

    </section> 
<section class="overflow-hidden md:pt-24 lg:pt-20 pb-2">
  <!-- Heading -->
  <div class="text-center px-4 mb-12 pt-10">
    <h1 class="font-skin-care text-2xl sm:text-4xl md:text-5xl lg:text-6xl text-pink-400 font-semibold tracking-wide" data-aos-delay="200" data-aos-duration="1300" data-aos="fade-right">
      Enjoy Shopping <span class="text-black" > With Our Website</span>
    </h1>
  </div>

  <!-- Image Grid -->
  <div class=" max-w-screen-xl 2xl:max-w-screen-3xl px-4 sm:px-6 lg:px-8 mx-auto ">
    <div class="grid grid-cols-2 py-5 sm:grid-cols-2 sm:py-5 md:py-28 md:grid-cols-4 gap-6  lg:py-32">
      <!-- Each image cell -->
      <a href="#_" class="w-full" data-aos-delay="200" data-aos-duration="700" data-aos="fade-right">
        <img src="https://deiji.co.nz/cdn/shop/collections/2_9fcc342a-bdea-4f66-8a9c-ad311888e03f.png?v=1735288941" 
             class="rounded-xl rotate-6 hover:rotate-0 hover:-translate-y-6 hover:scale-110 duration-500 object-cover aspect-[3/4] w-full" 
             alt="Product 1">
      </a>
      <a href="#_" class="w-full" data-aos-delay="200" data-aos-duration="700" data-aos="fade-right">
        <img src="https://www.cosmeticary.com/cdn/shop/files/Cosmeticary-Bruxelles-Anua-Heartleaf-Quercetinol-Pore-Deep-Cleansing-Foam_lifestyle.jpg?v=1745319910&width=1200" 
             class="rounded-xl -rotate-12 hover:rotate-0 hover:-translate-y-6 hover:scale-110 duration-500 object-cover aspect-[3/4] w-full" 
             alt="Product 2">
      </a>
      <a href="#_" class="w-full" data-aos-delay="200" data-aos-duration="700" data-aos="fade-left">
        <img src="https://sokoskins.shop/cdn/shop/files/ANUA-Peach-77-Essence-Toner-4_1200x1200.png?v=1740820411" 
             class="rounded-xl rotate-6 hover:rotate-0 hover:-translate-y-6 hover:scale-110 duration-500 object-cover aspect-[3/4] w-full" 
             alt="Product 3">
      </a>
      <a href="#_" class="w-full" data-aos-delay="200" data-aos-duration="700" data-aos="fade-left">
        <img src="https://www.kanvasbeauty.com.au/cdn/shop/files/8_f0caaae4-3729-4478-ae2b-8ddad455a21d_1200x.jpg?v=1714289274" 
             class="rounded-xl -rotate-12 hover:rotate-0 hover:-translate-y-6 hover:scale-110 duration-500 object-cover aspect-[3/4] w-full" 
             alt="Product 4">
      </a>
    </div>
  </div>
</section>
   <!-- card section  -->
   <section  id="products" class=" pb-5">
   
    <section class=" mx-5 ">
       
 <div class="px-4 mb-4 py-20" data-aos="fade-up" data-aos-delay="100" data-aos-duration="800">
  <h1 class="font-skin-care text-3xl sm:text-4xl md:text-5xl lg:text-5xl text-pink-400 font-semibold tracking-wide">
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

  <!-- comment view -->
   <?php
       include(' comment.php');
   ?>

<section id="features" class="py-20 ">
    <div class="container mx-auto px-4">
        <h2 class="text-4xl font-extrabold text-center mb-12 text-pink-400">Products Type</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <a href="productpage.php#faceSection" 
               class="button bg-white rounded-lg shadow-lg p-8 text-center border-2 border-pink-300 hover:bg-pink-100 transition duration-300">
                <h3 class="text-2xl font-bold mb-3 text-pink-400">Face Products</h3>
                <p class="text-gray-500">Glow up your skin with our fabulous face essentials — smooth, hydrate, and shine bright like a diamond!</p>
            </a>
            <a href="productpage.php#bodySection" 
               class="button bg-white rounded-lg shadow-lg p-8 text-center border-2 border-pink-300 hover:bg-pink-100 transition duration-300">
                <h3 class="text-2xl font-bold mb-3 text-pink-400">Body Products</h3>
                <p class="text-gray-500">Pamper your body from head to toe with silky lotions and scrubs that scream self-love and softness.</p>
            </a>
            <a href="productpage.php#hairSection" 
               class="button bg-white rounded-lg shadow-lg p-8 text-center border-2 border-pink-300 hover:bg-pink-100 transition duration-300">
                <h3 class="text-2xl font-bold mb-3 text-pink-400">Hair Products</h3>
                <p class="text-gray-500">Turn heads with luscious locks! Nourish, style, and slay every day with our hair care must-haves.</p>
            </a>
        </div>
    </div>
</section>


   <!-- footer -->
   <?php
       include('footer.php');
   ?>
<script>
  document.getElementById('shopNowBtn').addEventListener('click', () => {
    document.getElementById('products').scrollIntoView({ behavior: 'smooth' });
  });
</script>
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
</html>