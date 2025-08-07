<?php
session_start();
include('./server/connection.php');

// Get cart and filter out invalid items
$cart = array_filter($_SESSION['cart'] ?? [], function ($item) {
    return isset($item['id'], $item['price'], $item['quantity'], $item['name'], $item['image'], $item['description1']) 
        && $item['quantity'] > 0 
        && $item['price'] >= 0;
});

function limit_text($text, $limit = 50) {
    if (strlen($text) <= $limit) {
        return $text;
    }
    return substr($text, 0, $limit) . '...';
}


?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth bg-gray-100">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet" />
  <script src="https://unpkg.com/flowbite@2.3.0/dist/flowbite.min.js"></script>
  <link rel="stylesheet" href="./css/style.css">
  <title>Shopping Cart</title>
</head>
<body class="font-sans antialiased text-gray-800">
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
<?php if (isset($_GET['error']) && $_GET['error'] === 'out_of_stock'): ?>
<div id="popup-overlay" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
  <div class="bg-white w-80 rounded-lg shadow-lg text-center p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-2">Error</h2>
    <p class="text-gray-600 mb-4">This item is out of stock. Please try again later.</p>
    <button onclick="dismissPopup()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
      Dismiss
    </button>
  </div>
</div>
<script>
function dismissPopup() {
  const popup = document.getElementById('popup-overlay');
  if (popup) popup.remove();
  const url = new URL(window.location);
  url.searchParams.delete('error');
  window.history.replaceState({}, document.title, url);
}
</script>
<?php endif; ?>

<section class="py-12">
  <div class="mx-auto max-w-screen-xl px-4 lg:px-8">
    <div class="flex justify-between">
      <h2 class="text-3xl font-bold text-gray-900 mb-8"><span class="text-pink-400">Shopping</span> Cart</h2>
      <a href="/FN/e-commerce/homepage.php" class="rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100">
        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" >
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Cart Items -->
      <div class="lg:col-span-2 space-y-6">
        <div class="space-y-6">
          <?php if (count($cart) > 0): ?>
            <?php foreach ($cart as $item): ?>
              <div class="rounded-lg border border-gray-200  p-6 shadow-sm hover:shadow-md transition duration-300">
                <div class="md:flex md:items-center md:justify-between md:gap-6">
                  <a href="#" class="shrink-0">
                    <img class="h-20 w-20 object-cover" src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" />
                  </a>
                  <div class="flex-1 min-w-0 space-y-2 md:max-w-md">
                    <a href="#" class="text-base font-medium text-gray-900 hover:underline"><?= htmlspecialchars($item['name']) ?></a>
                    <div><a href="#" class="text-gray-900"><?= htmlspecialchars( limit_text($item['description1'],40)) ?></a></div>
                    <div class="flex gap-4 text-sm">
                      <a href="removefromcart.php?remove=<?= $item['id'] ?>" class="text-red-600 hover:underline">🗑️ Remove</a>
                    </div>
                  </div>
                  <div class="flex items-center space-x-2 mt-4 md:mt-0">
                    <form action="updatecart.php" method="post" class="flex items-center space-x-2">
                      <input type="hidden" name="id" value="<?= $item['id'] ?>">
                      <button name="decrease" class="h-8 w-8 rounded border text-lg font-bold bg-gray-100 hover:bg-gray-200">-</button>
                      <input type="text" value="<?= $item['quantity'] ?>" readonly class="w-10 text-center border rounded bg-gray-50" />
                      <button name="increase" class="h-8 w-8 rounded border text-lg font-bold bg-gray-100 hover:bg-gray-200">+</button>
                    </form>
                  </div>
                  <div class="text-end font-semibold text-gray-900 md:w-24">
                    $<?= number_format($item['price'] * $item['quantity'], 2) ?>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p class="text-gray-500">Your cart is empty.</p>
          <?php endif; ?>
        </div>
      </div>

      <!-- Summary Section -->
      <div class="space-y-6">
        <div class="rounded-lg border border-gray-200  p-6 shadow-md">
          <h3 class="text-xl font-semibold text-gray-900 mb-4">Order Summary</h3>
          <?php
            $subtotal = 0;
            foreach ($cart as $item) {
              $subtotal += $item['price'] * $item['quantity'];
            }
            $shipping = 8;
            $tax = round($subtotal * 0.1);
            $total = $subtotal + $shipping + $tax;
          ?>
          <ul class="space-y-2 text-sm text-gray-600">
            <li class="flex justify-between"><span>Subtotal</span><span class="font-medium text-gray-900">$<?= number_format($subtotal, 2) ?></span></li>
            <li class="flex justify-between"><span>Shipping</span><span class="font-medium text-gray-900">$<?= number_format($shipping, 2) ?></span></li>
            <li class="flex justify-between"><span>Tax (10%)</span><span class="font-medium text-gray-900">$<?= number_format($tax, 2) ?></span></li>
            <hr class="my-2 border-t" />
            <li class="flex justify-between text-base font-bold text-gray-900"><span>Total</span><span>$<?= number_format($total, 2) ?></span></li>
          </ul>

          <form action="payment.php" method="POST" class="mt-6">
            <input type="hidden" name="subtotal" value="<?= number_format($subtotal, 2, '.', '') ?>">
            <input type="hidden" name="shipping" value="<?= number_format($shipping, 2, '.', '') ?>">
            <input type="hidden" name="tax" value="<?= number_format($tax, 2, '.', '') ?>">
            <input type="hidden" name="total" value="<?= number_format($total, 2, '.', '') ?>">
            <button type="submit" class="w-full rounded-lg bg-pink-400 text-white text-center py-3 font-semibold hover:bg-pink-700 transition">
              Proceed to Checkout
            </button>
          </form>
          <div class="flex justify-center mt-4 text-sm text-gray-500">
            or <a href="./homepage.php#products" class="ml-1 text-pink-500 hover:underline">Continue Shopping</a>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>
</body>
</html>
