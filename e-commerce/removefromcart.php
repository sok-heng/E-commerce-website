<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_GET['remove'])) {
    $remove_id = (int)$_GET['remove'];

    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] === $remove_id) {
            unset($_SESSION['cart'][$key]);
            // Reindex array so no gaps
            $_SESSION['cart'] = array_values($_SESSION['cart']);
            break;
        }
    }
}

// Redirect back to cart page
header("Location: cart.php");
exit();
