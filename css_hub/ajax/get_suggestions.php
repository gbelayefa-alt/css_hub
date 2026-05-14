<?php
/*
 * Author: Harshini Lakshman 
 * Date: April 1, 2026
 * Description: this file handles getting suggestion from users
 */
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION["email"])) {
    echo json_encode([]);
    exit;
}

$email = $_SESSION["email"];

$stmt = $pdo->prepare("
    SELECT subject, message, submitted_at
    FROM event_suggestions
    WHERE email = ?
    ORDER BY submitted_at DESC
");
$stmt->execute([$email]);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
