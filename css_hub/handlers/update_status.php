<?php
/*
 * Name: Daniel Yu
 * Date: April 19, 2026
 * Description: Updates order status in merch_orders, then redirects back to admin panel.
 */
session_start();
require_once '../includes/db.php';

$admin_email = 'gbea@mcmaster.ca';
if (!isset($_SESSION['email']) || $_SESSION['email'] != $admin_email) {
    header('Location: ../public/login.php');
    exit;
}

$order_id = filter_input(INPUT_POST, 'order_id', FILTER_VALIDATE_INT);
$status = $_POST['status'] ?? '';

if (!$order_id || !in_array($status, ['pending', 'paid', 'shipped'])) {
    header('Location: ../admin/admin_orders.php?error=invalid');
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE merch_orders SET status = ? WHERE order_id = ?");
    $stmt->execute([$status, $order_id]);
    header('Location: ../admin/admin_orders.php?success=updated');
} catch (PDOException $e) {
    header('Location: ../admin/admin_orders.php?error=db');
}
exit;
