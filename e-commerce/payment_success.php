<?php
session_start();

// Redirect if no order data
if (!isset($_SESSION['order_success'])) {
    header("Location: homepage.php");
    exit();
}

// Set timezone to Cambodia
date_default_timezone_set('Asia/Phnom_Penh');

$order = $_SESSION['order_success'];
$userName = $_SESSION['user_name'] ?? 'Customer'; // fallback if userName is not set
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Payment Confirmation</title>
    <script>
        function printReceipt() {
            window.print();
        }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .animate-fadeInUp {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        @keyframes pop {
            0% {
                transform: scale(0.5);
                opacity: 0;
            }
            50% {
                transform: scale(1.2);
                opacity: 1;
            }
            100% {
                transform: scale(1);
            }
        }

        .animate-pop {
            animation: pop 0.5s ease-out forwards;
        }

        .animate-delay-1 { animation-delay: 0.2s; }
        .animate-delay-2 { animation-delay: 0.4s; }
        .animate-delay-3 { animation-delay: 0.6s; }
        .animate-delay-4 { animation-delay: 0.8s; }
    </style>
</head>
<body class="bg-pink-50 min-h-screen flex items-center justify-center px-4">

    <div class="bg-white shadow-2xl rounded-3xl p-10 max-w-lg w-full text-center border-4 border-pink-300 animate-fadeInUp">
        <div class="text-pink-600 text-6xl mb-6 animate-pop">✅</div>

        <h1 class="text-3xl font-extrabold mb-4 text-pink-700 animate-fadeInUp">Payment Successful!</h1>

        <p class="text-gray-700 mb-8 text-lg animate-fadeInUp animate-delay-1">
            Thank you, <strong class="text-pink-600"><?= htmlspecialchars($userName) ?></strong>.
        </p>

        <div class="text-left text-gray-600 text-sm mb-8 space-y-3 border-t border-pink-200 pt-6">
            <p class="animate-fadeInUp animate-delay-2">
                <span class="font-semibold text-pink-700">Order Total:</span> $<?= number_format($order['total'], 2) ?>
            </p>
            <p class="animate-fadeInUp animate-delay-3">
                <span class="font-semibold text-pink-700">Card Ending In:</span> <?= htmlspecialchars($order['last4']) ?>
            </p>
            <p class="animate-fadeInUp animate-delay-4">
                <span class="font-semibold text-pink-700">Order ID:</span> <?= htmlspecialchars($order['order_id'] ?? 'N/A') ?>
            </p>
            <p class="animate-fadeInUp animate-delay-4">
                <span class="font-semibold text-pink-700">Date:</span> <?= date('F j, Y, g:i a') ?>
            </p>
        </div>

        <div class="flex justify-center gap-4 animate-fadeInUp animate-delay-4">
            <button
                onclick="printReceipt()"
                class="bg-pink-500 hover:bg-pink-600 transition text-white px-6 py-3 rounded-lg font-semibold shadow-md"
                aria-label="Print Receipt"
            >
                🧾 Print Receipt
            </button>

            <a
                href="homepage.php"
                class="inline-block px-6 py-3 rounded-lg border-2 border-pink-500 text-pink-600 font-semibold hover:bg-pink-600 hover:text-white transition"
                aria-label="Continue Shopping"
            >
                🛒 Continue Shopping
            </a>
        </div>
    </div>

</body>
</html>
