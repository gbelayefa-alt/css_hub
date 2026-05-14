<?php
/*
 * Author: Harshini Lakshman 
 * Date: April 1, 2026
 * Description: this file handles getting form responses from users
 */
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION["email"])) {
    echo json_encode([]);
    exit;
}

$email = $_SESSION["email"];
$stmt = $pdo->prepare("
    SELECT form_id, MAX(submitted_at) as submitted_at
    FROM form_responses
    WHERE email = ?
    GROUP BY form_id
    ORDER BY submitted_at DESC
");
$stmt->execute([$email]);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));