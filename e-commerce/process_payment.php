<?php
// process_payment.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include('./server/connection.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize input
    $full_name   = trim($_POST['card_name'] ?? '');
    $card_number = preg_replace('/\D/', '', $_POST['card_number'] ?? '');
    $expiry      = trim($_POST['expiry'] ?? '');
    $cvv         = trim($_POST['cvv'] ?? '');
    $address     = trim($_POST['address'] ?? '');

    $subtotal = isset($_POST['subtotal']) ? floatval($_POST['subtotal']) : 0.00;
    $shipping = isset($_POST['shipping']) ? floatval($_POST['shipping']) : 0.00;
    $tax      = isset($_POST['tax']) ? floatval($_POST['tax']) : 0.00;
    $total    = isset($_POST['total']) ? floatval($_POST['total']) : 0.00;

    // Validation
    $errors = [];

    if (empty($full_name)) {
        $errors[] = "Cardholder name is required.";
    }

    if (strlen($card_number) < 12 || strlen($card_number) > 19) {
        $errors[] = "Invalid card number.";
    }

    if (!preg_match('/^\d{2}\/\d{2}$/', $expiry)) {
        $errors[] = "Invalid expiry format. Use MM/YY.";
    }

    if (strlen($cvv) < 3 || strlen($cvv) > 4 || !ctype_digit($cvv)) {
        $errors[] = "Invalid CVV.";
    }

    if (empty($address)) {
        $errors[] = "Address is required.";
    }

    if ($total <= 0) {
        $errors[] = "Invalid total amount.";
    }

    if (!empty($errors)) {
        echo "<h2 class='text-red-600 text-xl font-bold'>❌ Payment Error</h2>";
        echo "<ul class='text-red-500 list-disc pl-5'>";
        foreach ($errors as $e) {
            echo "<li>" . htmlspecialchars($e) . "</li>";
        }
        echo "</ul>";
        exit();
    }

    // Extract last 4 digits of card
    $card_last4 = substr($card_number, -4);

    // Get user ID if logged in
    $user_id = $_SESSION['user_id'] ?? null;

    $status = 'Pending'; // default status

    // Insert into orders table (with status)
    $stmt = $conn->prepare("
        INSERT INTO orders (user_id, full_name, card_last4, address, subtotal, shipping, tax, total, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    if ($stmt === false) {
        die("Database error (order insert): " . $conn->error);
    }

    $stmt->bind_param("isssdddds", $user_id, $full_name, $card_last4, $address, $subtotal, $shipping, $tax, $total, $status);

    if ($stmt->execute()) {
        $order_id = $stmt->insert_id;

        if (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                $product_id = intval($item['id'] ?? 0);
                $quantity   = intval($item['quantity'] ?? 1);

                if ($product_id > 0 && $quantity > 0) {
                    // 1. Get product info
                    $productQuery = $conn->prepare("SELECT name, image_url, price, stock FROM products WHERE id = ?");
                    $productQuery->bind_param("i", $product_id);
                    $productQuery->execute();
                    $productQuery->store_result();

                    if ($productQuery->num_rows > 0) {
                        $productQuery->bind_result($product_name, $product_image, $product_price, $product_stock);
                        $productQuery->fetch();
                        $productQuery->close();

                        // 2. Check stock
                        if ($product_stock < $quantity) {
                            echo "<h2 class='text-red-600 text-xl font-bold'>❌ Not enough stock for product ID {$product_id}</h2>";
                            exit();
                        }

                        // 3. Update stock
                        $updateStock = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
                        if ($updateStock === false) {
                            die("Stock update failed: " . $conn->error);
                        }
                        $updateStock->bind_param("ii", $quantity, $product_id);
                        $updateStock->execute();
                        $updateStock->close();

                        // 4. Insert into order_items
                        $insertItem = $conn->prepare("
                            INSERT INTO order_items 
                            (order_id, product_id, product_name, product_image, quantity, price)
                            VALUES (?, ?, ?, ?, ?, ?)
                        ");
                        if ($insertItem === false) {
                            die("Insert into order_items failed: " . $conn->error);
                        }
                        $insertItem->bind_param("iissid", $order_id, $product_id, $product_name, $product_image, $quantity, $product_price);
                        $insertItem->execute();
                        $insertItem->close();
                    } else {
                        echo "<h2 class='text-red-600 text-xl font-bold'>❌ Product not found (ID: {$product_id})</h2>";
                        exit();
                    }
                }
            }
        }

        // Store success info in session
        $_SESSION['order_success'] = [
            'order_id' => $order_id,
            'name'     => $full_name,
            'last4'    => $card_last4,
            'total'    => $total,
            'address'  => $address,
            'status'   => $status
        ];

        // Clear cart
        unset($_SESSION['cart']);

        // Redirect
        header("Location: payment_success.php");
        exit();
    } else {
        echo "<h2 class='text-red-600 text-xl font-bold'>❌ Payment Failed</h2>";
        echo "<p class='text-red-500'>Error: " . htmlspecialchars($stmt->error) . "</p>";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: payment.php");
    exit();
}
?>
