<?php
session_start();
include('./server/connection.php'); // your DB connection

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    if ($id > 0) {
        // Fetch real stock from DB
        $stmt = $conn->prepare("SELECT stock FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();

        if ($product) {
            $real_stock = (int)$product['stock'];

            foreach ($_SESSION['cart'] as &$item) {
                if ($item['id'] === $id) {
                    if (isset($_POST['increase'])) {
                        if ($item['quantity'] < $real_stock) {
                            $item['quantity'] += 1;
                        }
                    } elseif (isset($_POST['decrease'])) {
                        if ($item['quantity'] > 1) {
                            $item['quantity'] -= 1;
                        }
                    }
                    break;
                }
            }
            unset($item);
        }
    }
}

header("Location: cart.php");
exit();
