<?php
session_start();
include('./server/connection.php');

if (!isset($_SESSION['user_email'])) {
    header("Location: loginpage.php?message=login_required");
    exit();
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// ✅ CLEAR all products from cart
if (isset($_GET['clear_cart']) && $_GET['clear_cart'] == '1') {
    $_SESSION['cart'] = [];

    // Redirect back to previous page or cart
    $redirect = $_SERVER['HTTP_REFERER'] ?? 'cart.php';
    header("Location: $redirect");
    exit();
}

// ✅ REMOVE product from cart
if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {
    $remove_id = (int)$_GET['remove'];
    foreach ($_SESSION['cart'] as $index => $item) {
        if ($item['id'] === $remove_id) {
            unset($_SESSION['cart'][$index]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex the cart array
            break;
        }
    }

    // Redirect back to previous page or cart
    $redirect = $_SERVER['HTTP_REFERER'] ?? 'cart.php';
    header("Location: $redirect");
    exit();
}

// ✅ ADD product to cart
if (isset($_GET['add'])) {
    $product_id = (int)$_GET['add'];

    $stmt = $conn->prepare("SELECT id, name, price, stock, image_url, description1 FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($product = $result->fetch_assoc()) {
        // Redirect to cart.php with error if out of stock
        if ((int)$product['stock'] <= 0) {
            header("Location: cart.php?error=out_of_stock");
            exit();
        }

        $found = false;

        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] === $product_id) {
                if ($item['quantity'] < $product['stock']) {
                    $item['quantity'] += 1;
                }
                $found = true;
                break;
            }
        }

        if (!$found && $product['stock'] > 0) {
            $_SESSION['cart'][] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'description1' => $product['description1'],
                'image' => $product['image_url'] ?: 'https://via.placeholder.com/150',
                'price' => (float)$product['price'],
                'quantity' => 1,
                'stock' => (int)$product['stock']
            ];
        }
    }

    header("Location: cart.php");
    exit();
}
