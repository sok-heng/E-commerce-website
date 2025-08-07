<?php
session_start();
include('./server/connection.php');

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link
      href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css"
      rel="stylesheet"
    />
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <body>
    <div class="px-4">
      <div class="w-full"> <!-- Full width container -->
        <div class="my-8">
          <div
            id="scrollContainer"
            class="flex flex-no-wrap overflow-x-scroll scrolling-touch items-start mb-8"
          >
           <?php
    $query = "SELECT * FROM products WHERE type IN ('face', 'body', 'hair') ORDER BY RAND() ";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 0) {
        echo "<p class='text-red-500 col-span-full'>No products found for selected types.</p>";
    }

    while ($row = mysqli_fetch_assoc($result)) :
    ?>
          <!-- card -->
          <div class="flex-none w-[89vw] sm:w-[50vw] md:w-[30vw] lg:w-[23.5vw] mr-4 border rounded-lg">
  <div class="w-full border border-pink-300 rounded-lg shadow-md p-4">
    <!-- Discount + Wishlist -->
    <div class="relative">
      <!-- Discount Badge -->
   <?php if ($row['stock'] > 0): ?>
  <span class="absolute   bg-pink-500 text-white text-xs font-semibold px-2 py-1 rounded-md">
    Available
  </span>
<?php else: ?>
  <span class="absolute top-2 left-2 bg-red-500 text-white text-xs font-semibold px-2 py-1 rounded-full">
    Out of Stock
  </span>
<?php endif; ?>

      <!-- Wishlist Icon -->
      <button class="absolute top-2 right-2 w-8 h-8 bg-white rounded-full shadow flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z" />
        </svg>
      </button>
      <!-- Product Image -->
     <div class="aspect-[4/3] w-full">
  <img src="<?= htmlspecialchars($row['image_url']) ?>" alt="Product Image" class="object-cover w-full h-full rounded-md" />
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
   <p> Type:<span class="uppercase text-green-600 text-xs font-medium"> <?= strtoupper($row['type']) ?></span></p>

      <!-- Ratings -->
      <div class="flex space-x-1 text-orange-500 text-sm">
        <!-- 4 filled stars + 1 gray -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927C9.349 2.2 10.651 2.2 10.951 2.927..."/></svg>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="..."/></svg>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="..."/></svg>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="..."/></svg>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path d="..."/></svg>
      </div>

      <!-- Price + Cart -->
      <div class="flex items-center justify-between mt-2">
        <div class="flex items-baseline space-x-2">
          <span class="text-pink-600 text-xl font-semibold">  $<?= number_format($row['price'], 2) ?></span>
          <span class="text-gray-400 text-sm line-through">$1500.00</span>
        </div>
        <button class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center shadow text-white">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
            <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
            <path d="M17 17h-11v-14h-2" />
            <path d="M6 5l14 1l-1 7h-13" />
          </svg>
        </button>
      </div>
    </div>
  </div>
</div>

             <?php endwhile; ?>
            

            <!-- Repeat for more cards if needed -->
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
