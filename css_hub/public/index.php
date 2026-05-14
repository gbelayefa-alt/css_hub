<?php
/*
 * Name: Daniel Yu
 * Date: April 2, 2026
 * Description: Home page for CSS Web Hub.
 */
session_start();
require_once '../includes/db.php';
require_once '../includes/header.php';

$upcomingEvents = [];
try {
    $stmt = $pdo->prepare("
        SELECT event_title, event_date, event_time, location
        FROM events
        WHERE event_date >= CURDATE()
        ORDER BY event_date ASC
        LIMIT 3
    ");
    $stmt->execute();
    $upcomingEvents = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $upcomingEvents = [];
}
?>


<div class="home-hero">
    <h1>Welcome to the CSS Web Hub</h1>
    <p>Your central hub for Computer Science Society events, resources, forms, and merchandise.</p>
    <?php if (isset($_SESSION['email'])): ?>
        <p class="logged-in-msg">Hello, <?= htmlspecialchars($_SESSION['first_name'] ?? 'Student') ?>! <a href="dash.php">Go to Dashboard</a></p>
    <?php else: ?>
        <p><a href="login.php" class="btn-primary">Login</a></p>
    <?php endif; ?>
</div>

<div class="home-grid">
    <div class="home-card">
        <h2>Upcoming Events</h2>
        <?php if (count($upcomingEvents) > 0): ?>
            <ul>
                <?php foreach ($upcomingEvents as $event): ?>
                    <li>
                        <strong><?= htmlspecialchars($event['event_title']) ?></strong><br>
                        <?= htmlspecialchars($event['event_date']) ?> at <?= htmlspecialchars($event['event_time']) ?><br>
                        Location: <?= htmlspecialchars($event['location']) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No upcoming events at the moment. Check back later!</p>
        <?php endif; ?>
        <a href="events.php" class="home-link">View all events →</a>
    </div>

    <div class="home-card">
        <h2>Academic Resources</h2>
        <p>Access notes, slides, and practice materials for your courses.</p>
        <a href="resources.php" class="home-link">Browse resources →</a>
    </div>

    <div class="home-card">
        <h2>Get Involved</h2>
        <p>Apply for executive positions, suggest events, or order CSS merchandise.</p>
        <a href="forms.php" class="home-link">Fill out forms →</a>
        <a href="merch-order.php" class="home-link">Shop merchandise →</a>
    </div>
</div>



<?php require_once '../includes/footer.php'; ?>