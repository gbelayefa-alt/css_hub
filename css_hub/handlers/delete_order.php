<?php
/*
 * Name: Daniel Yu
 * Date: April 19, 2026
 * Description: Admin delete order.
 */
session_start();
require_once '../includes/db.php';

$admin_email = 'gbea@mcmaster.ca';
if (!isset($_SESSION['email']) || $_SESSION['email'] != $admin_email) {
    header('Location: ../public/login.php');
    exit;
}

$order_id = filter_input(INPUT_POST, 'order_id', FILTER_VALIDATE_INT);
if ($order_id) {
    $stmt = $pdo->prepare("DELETE FROM merch_orders WHERE order_id = ?");
    $stmt->execute([$order_id]);
}
header('Location: ../admin/admin_orders.php');
exit;
?>