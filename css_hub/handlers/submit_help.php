<?php
/*
 * Name: Amanda Gbe
 * Date: April 18, 2026
 * Description: Handler for processing help messages submitted through the contact form. Validates input and saves messages to the database for admin review.
 */
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['email'])) {
    header('Location: ../public/login.php');
    exit;
}

$email = $_SESSION['email'];

$subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_SPECIAL_CHARS);
$message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS);

if (!$subject || !$message) {
    die("Invalid input");
}

$stmt = $pdo->prepare("
    INSERT INTO help_messages (email, subject, message) 
    VALUES (?, ?, ?)
");
$stmt->execute([$email, $subject, $message]);

header('Location: ../public/contact.php?success=1');
exit;


