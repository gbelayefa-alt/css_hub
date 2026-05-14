<?php
/*
 * Name: Harshini Lakshman 
 * Date: March 31st, 2026
 * Description: handles the suggestion submission process
 */
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION["email"])) {
    die("Error: User not logged in.");
}

$email = $_SESSION["email"];
$subject = trim($_POST["subject"] ?? "");
$message = trim($_POST["message"] ?? "");

try {
    $stmt = $pdo->prepare("
        INSERT INTO event_suggestions (email, subject, message, submitted_at)
        VALUES (?, ?, ?, NOW())
    ");
    $stmt->execute([$email, $subject, $message]);
    header("Location: ../public/dash.php?success=suggestion");
    exit;
} catch (Exception $e) {
    die("Error submitting suggestion: " . $e->getMessage());
}
?>