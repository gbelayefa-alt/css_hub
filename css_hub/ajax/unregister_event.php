<?php
/*
 * Author: Amanda Gbe
 * Date: April 17, 2026
 * Description: Handles AJAX request to unregister from an event. 
 *              Validates user session and event ID, then deletes registration from database. 
 */
session_start();
require_once '../includes/db.php';

header('Content-Type: application/json');
if (!isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}
$email = $_SESSION['email'];
$event_id = $_POST['event_id'] ?? null;


if (!$event_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}
try {
    $stmt = $pdo->prepare("
        DELETE FROM event_registrations
        WHERE email = :email AND event_id = :event_id"
    );
    $stmt->execute([
        'email' => $email,
        'event_id' => $event_id
    ]);

    //INSERT INTO unregister_logs
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
