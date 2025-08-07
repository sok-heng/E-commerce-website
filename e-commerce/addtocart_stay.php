<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include('./server/connection.php');

// Redirect if not logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: loginpage.php?message=login_required");
    exit();
}

// Create empty cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Helper to clean redirect URL query params (remove error)
function cleanRedirectUrl($url) {
    $parsed_url = parse_url($url);
    $base = $parsed_url['path'] ?? '';
    $query = [];

    if (isset($parsed_url['query'])) {
        parse_str($parsed_url['query'], $query);
        unset($query['error']); // Remove error param if exists
    }

    $clean_url = $base;
    if (!empty($query)) {
        $clean_url .= '?' . http_build_query($query);
    }
    return $clean_url;
}

// Function to preserve openCart=1 param in redirect if set
function preserveOpenCartParam($url) {
    $parsed_url = parse_url($url);
    $base = $parsed_url['path'] ?? '';
    $query = [];

    if (isset($parsed_url['query'])) {
        parse_str($parsed_url['query'], $query);
    }

    if (isset($_GET['openCart']) && $_GET['openCart'] == '1') {
        $query['openCart'] = '1';
    }

    $final_url = $base;
    if (!empty($query)) {
        $final_url .= '?' . http_build_query($query);
    }
    return $final_url;
}

// Process clear cart request
if (isset($_GET['clear_cart']) && $_GET['clear_cart'] == '1') {
    $_SESSION['cart'] = [];

    $redirect = $_GET['redirect'] ?? 'index.php';
    $redirect = cleanRedirectUrl($redirect);
    $redirect = preserveOpenCartParam($redirect);

    header("Location: $redirect");
    exit();
}

// Process remove-from-cart request
if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {
    $remove_id = (int)$_GET['remove'];
    foreach ($_SESSION['cart'] as $index => $item) {
        if ($item['id'] === $remove_id) {
            unset($_SESSION['cart'][$index]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex
            break;
        }
    }

    $redirect = $_GET['redirect'] ?? 'index.php';
    $redirect = cleanRedirectUrl($redirect);
    $redirect = preserveOpenCartParam($redirect);

    header("Location: $redirect");
    exit();
}

// Process add-to-cart request
if (isset($_GET['add'])) {
    $product_id = (int)$_GET['add'];

    $stmt = $conn->prepare("SELECT id, name, price, stock, image_url, description1 FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($product = $result->fetch_assoc()) {

        // Redirect with error if stock is 0
        if ((int)$product['stock'] <= 0) {
            $redirect = $_GET['redirect'] ?? 'index.php';
            $redirect .= (strpos($redirect, '?') !== false ? '&' : '?') . 'error=out_of_stock';
            header("Location: $redirect");
            exit();
        }

        $found = false;

        // Check if product already exists in cart
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] === $product_id) {
                if ($item['quantity'] < $product['stock']) {
                    $item['quantity'] += 1;
                }
                $found = true;
                break;
            }
        }

        // Add new item to cart if not found
        if (!$found) {
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

    // Clean redirect URL from error on success
    $back = $_GET['redirect'] ?? 'index.php';
    $clean_url = cleanRedirectUrl($back);
    $clean_url = preserveOpenCartParam($clean_url);

    header("Location: $clean_url");
    exit();
}
