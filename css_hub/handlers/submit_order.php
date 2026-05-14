<?php
/*
 * Name: Daniel Yu
 * Date: March 25, 2026
 * Description: Processes merchandise order form, inserts into merch_orders table.
 */
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['email'])) {
    header('Location: ../public/login.php');
    exit;
}

$email = $_SESSION['email'];
$item_name  = trim($_POST['item_name'] ?? '');
$size       = trim($_POST['size'] ?? '');
$quantity   = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);

if (empty($item_name) || !$quantity || $quantity < 1) {
    header('Location: ../public/merch-order.php?error=invalid');
    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO merch_orders (email, item_name, size, quantity, order_date, status)
        VALUES (?, ?, ?, ?, NOW(), 'pending')
    ");
    $stmt->execute([$email, $item_name, $size, $quantity]);
    header('Location: ../public/order-status.php?success=order_placed');
} catch (PDOException $e) {
    error_log("Order insert failed: " . $e->getMessage());
    header('Location: ../public/merch-order.php?error=db');
}
exit;
