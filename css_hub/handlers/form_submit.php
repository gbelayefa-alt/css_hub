<?php
/*
 * Name: Harshini Lakshman 
 * Date: March 31st, 2026
 * Description: handles the form submission process
 */
session_start();
require_once '../includes/db.php';

// check login
if (!isset($_SESSION["email"])) {
    die("Error: User not logged in.");
}

$email = $_SESSION["email"];
$form_id = filter_input(INPUT_POST, "form_id", FILTER_VALIDATE_INT);
$answers = $_POST["answers"] ?? [];

try {
    foreach ($answers as $question_id => $response_text) {
        $stmt = $pdo->prepare("
            INSERT INTO form_responses
            (email, form_id, question_id, response_text, submitted_at)
            VALUES (?, ?, ?, ?, NOW())
        ");
        $stmt->execute([$email, $form_id, $question_id, $response_text]);
    } 
} catch (Exception $e) {
    die("Error submitting form: " . $e->getMessage());
}

header("Location: ../public/dash.php");
exit();
?>