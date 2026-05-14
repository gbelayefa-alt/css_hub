<?php
/*
 * Name: Daniel Yu
 * Date: April 2, 2026
 * Description: AJAX endpoint - logs resource access in resource_usage table.
 */
session_start();
require_once '../includes/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['email'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$email = $_SESSION['email'];
$resource_id = filter_input(INPUT_POST, 'resource_id', FILTER_VALIDATE_INT);

if (!$resource_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid resource ID']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO resource_usage (email, resource_id, date_accessed) VALUES (?, ?, NOW())");
    $stmt->execute([$email, $resource_id]);
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}
?>