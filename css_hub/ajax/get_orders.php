<?php
/*
 * Name: Daniel Yu
 * Date: April 2, 2026
 * Description: this file handles getting order responses from users.
 */
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION["email"])) {
    echo json_encode([]);
    exit;
}

$email = $_SESSION["email"];

$stmt = $pdo->prepare("
    SELECT order_id, item_name, size, quantity, status, order_date
    FROM merch_orders
    WHERE email = ?
    ORDER BY order_date DESC
");
$stmt->execute([$email]);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));