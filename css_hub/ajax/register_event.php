<?php
/*
 * Author: Amanda Gbe
 * Date: April 2, 2026
 * Description: this file handles AJAX event registration requests
 */

session_start();
require_once '../includes/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['email'])) {
    echo json_encode([
        'success' => false, 
        'message' => 'You must be logged in first'
    ]);
    exit;
}

$email = $_SESSION['email'];
$event_id = $_POST['event_id'] ?? '';

if (!ctype_digit($event_id)) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid event ID'
    ]);
    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO event_registrations (email, event_id)
        VALUES (:email, :event_id)
    ");
    $stmt->execute([
        'email' => $email,
        'event_id' => $event_id
    ]);

    $eventStmt = $pdo->prepare("
        SELECT event_title, event_date, event_time, location
        FROM events
        WHERE event_id = :event_id
    ");
    $eventStmt->execute(['event_id' => $event_id]);
    $event = $eventStmt->fetch(PDO::FETCH_ASSOC);

    $row_html = '';
    if ($event) {
        $row_html = '<tr>'
            . '<td>' . htmlspecialchars($event['event_title']) . '</td>'
            . '<td>' . htmlspecialchars($event['event_date']) . '</td>'
            . '<td>' . htmlspecialchars($event['event_time']) . '</td>'
            . '<td>' . htmlspecialchars($event['location']) . '</td>'
            . '</tr>';
    }

    echo json_encode([
        'success' => true,
        'message' => 'Successfully registered for the event',
        'row_html' => $row_html, 
        'event_id' => $event_id
    ]);

}catch (PDOException $e) {
    echo json_encode ([
        'success' => false,
        'message' => 'You already registered for this event or an error occurred'
    ]);
}

?>