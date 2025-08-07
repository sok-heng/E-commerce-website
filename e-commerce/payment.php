<?php
$subtotal = isset($_POST['subtotal']) ? number_format($_POST['subtotal'], 2, '.', '') : '0.00';
$shipping = isset($_POST['shipping']) ? number_format($_POST['shipping'], 2, '.', '') : '0.00';
$tax = isset($_POST['tax']) ? number_format($_POST['tax'], 2, '.', '') : '0.00';
$total = isset($_POST['total']) ? number_format($_POST['total'], 2, '.', '') : '0.00';

// Suppose you already have $product somewhere loaded, e.g., $product['id']
// For demo, I’ll just hardcode a product id = 1
$product_id = 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Payment</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-white">
<section class="py-8 antialiased bg-gray-800 md:py-16 p-28 rounded-lg">
  <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
    <div class="mx-auto max-w-5xl">
      <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">Payment</h2>

      <div class="mt-6 sm:mt-8 lg:flex lg:items-start lg:gap-12">
        <form id="paymentForm" action="process_payment.php" method="POST" class="w-full rounded-lg border border-gray-200 bg-white p-4 shadow-sm sm:p-6 lg:max-w-xl lg:p-8">
          
          <!-- Address Field -->
          <div class="mb-6">
            <label for="address" class="mb-2 block text-sm font-medium text-gray-900">Delivery Address*</label>
            <textarea 
              id="address" 
              name="address" 
              rows="3" 
              class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900" 
              placeholder="123 Main St, Phnom Penh, Cambodia" 
              required
            ></textarea>
          </div>

          <!-- Card Info -->
          <div class="mb-6 grid grid-cols-2 gap-4">
            <div class="col-span-2 sm:col-span-1">
              <label for="full_name" class="mb-2 block text-sm font-medium text-gray-900">Full name (as displayed on card)*</label>
              <input type="text" id="full_name" name="card_name" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900" placeholder="Bonnie Green" required />
            </div>

            <div class="col-span-2 sm:col-span-1">
              <label for="card-number-input" class="mb-2 block text-sm font-medium text-gray-900">Card number*</label>
              <input type="text" id="card-number-input" name="card_number" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900" placeholder="xxxx-xxxx-xxxx-xxxx" required />
            </div>

            <div>
              <label for="card-expiration-input" class="mb-2 block text-sm font-medium text-gray-900">Card expiration*</label>
              <input type="text" id="card-expiration-input" name="expiry" placeholder="12/24" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900" required />
            </div>

            <div>
              <label for="cvv-input" class="mb-2 block text-sm font-medium text-gray-900">CVV*</label>
              <input type="number" id="cvv-input" name="cvv" placeholder="•••" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900" required />
            </div>
          </div>

          <!-- Pass order summary values to process_payment.php -->
          <input type="hidden" name="subtotal" value="<?= $subtotal ?>">
          <input type="hidden" name="shipping" value="<?= $shipping ?>">
          <input type="hidden" name="tax" value="<?= $tax ?>">
          <input type="hidden" name="total" value="<?= $total ?>">

          <!-- Product ID hidden -->
          <input type="hidden" name="product_id" value="<?= htmlspecialchars($product_id) ?>">

          <button 
            id="payButton" 
            type="submit" 
            class="flex w-full items-center justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700"
          >
            Pay now
          </button>
        </form>

        <!-- Order Summary -->
        <div class="mt-6 grow sm:mt-8 lg:mt-0">
          <div class="space-y-4 rounded-lg border border-gray-100 bg-gray-50 p-6">
            <dl class="flex items-center justify-between gap-4">
              <dt class="text-base font-normal text-gray-500">Subtotal</dt>
              <dd class="text-base font-medium text-gray-900">$<?= $subtotal ?></dd>
            </dl>
            <dl class="flex items-center justify-between gap-4">
              <dt class="text-base font-normal text-gray-500">Shipping</dt>
              <dd class="text-base font-medium text-gray-900">$<?= $shipping ?></dd>
            </dl>
            <dl class="flex items-center justify-between gap-4">
              <dt class="text-base font-normal text-gray-500">Tax</dt>
              <dd class="text-base font-medium text-gray-900">$<?= $tax ?></dd>
            </dl>
            <hr class="my-2 border-t border-gray-200" />
            <dl class="flex items-center justify-between gap-4">
              <dt class="text-base font-bold text-gray-900">Total</dt>
              <dd class="text-base font-bold text-gray-900">$<?= $total ?></dd>
            </dl>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- Submit Button Animation Script -->
<script>
  const form = document.getElementById('paymentForm');
  const payButton = document.getElementById('payButton');

  form.addEventListener('submit', function(e) {
    e.preventDefault();

    payButton.disabled = true;
    payButton.innerHTML = `
      <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 010 16v-4l-3 3 3 3v-4a8 8 0 01-8-8z"></path>
      </svg>
      Processing...
    `;

    setTimeout(() => {
      form.submit();
    }, 1500);
  });
</script>
</body>
</html>
