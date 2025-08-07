<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include('./server/connection.php');

// Check if form submitted with required data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $order_id = (int) $_POST['order_id'];
    $status = $_POST['status'];

    // Validate status value
    $allowed_status = ['Pending', 'Deliveried', 'Canceled'];
    if (!in_array($status, $allowed_status)) {
        die('Invalid status value.');
    }

    // Update the order status securely using prepared statements
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param('si', $status, $order_id);
    
    if ($stmt->execute()) {
        // Redirect back to orders page (change URL if needed)
        header('Location: orders.php?update=success');
        exit();
    } else {
        echo "Error updating status: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}
?>
