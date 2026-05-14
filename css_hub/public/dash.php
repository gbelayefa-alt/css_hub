<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/*
 * Name: Harshini Lakshman
 * Date: March 31st, 2026
 * Description: this file handles the dashboard page of the CSS hub
 */

session_start();
require_once '../includes/db.php';

// check login
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}

$email = $_SESSION["email"];
$first_name = $_SESSION["first_name"] ?? "Student";

// Registered events
$stmt = $pdo->prepare("
    SELECT e.event_title, e.event_date, e.event_time, e.location
    FROM event_registrations er
    JOIN events e ON er.event_id = e.event_id
    WHERE er.email = ?
    AND e.event_date >= CURDATE()
    ORDER BY e.event_date ASC, e.event_time ASC
");
$stmt->execute([$email]);
$upcomingEvents = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Completed forms
$stmt = $pdo->prepare("
    SELECT f.form_title, fr.form_id, MAX(fr.submitted_at) AS submitted_at
    FROM form_responses fr
    JOIN forms f ON fr.form_id = f.form_id
    WHERE fr.email = ?
    GROUP BY fr.form_id, f.form_title
    ORDER BY submitted_at DESC
");
$stmt->execute([$email]);
$form = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Suggestions
$stmt = $pdo->prepare("
    SELECT subject, message, submitted_at
    FROM event_suggestions
    WHERE email = ?
    ORDER BY submitted_at DESC
");
$stmt->execute([$email]);
$suggestions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Merchandise orders
$stmt = $pdo->prepare("
    SELECT order_id, item_name, size, quantity, status, order_date
    FROM merch_orders
    WHERE email = ?
    ORDER BY order_date DESC
");
$stmt->execute([$email]);
$merchOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Academic resources used
$stmt = $pdo->prepare("
    SELECT r.resource_title, r.course_code, ru.date_accessed
    FROM resource_usage ru
    JOIN resources r ON ru.resource_id = r.resource_id
    WHERE ru.email = ?
    ORDER BY ru.date_accessed DESC
");
$stmt->execute([$email]);
$accessedResources = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header2.php';
?>

<div class="dashboard-page">
    <div class="dashboard-hero">
        <h1>My Dashboard</h1>
        <p class="dashboard-subtitle">Welcome back, <?php echo htmlspecialchars($first_name); ?>!</p>
    </div>

    <div class="dashboard-grid">
        <!-- Upcoming Events -->
        <div class="dashSection">
            <h2>My Upcoming Events</h2>

            <?php if (count($upcomingEvents) === 0): ?>
                <p>No upcoming registered events.</p>
            <?php else: ?>
                <div class="schedule-list">
                    <?php foreach ($upcomingEvents as $event): ?>
                        <div class="schedule-item">
                            <div class="schedule-date">
                                <?php echo date("M d", strtotime($event["event_date"])); ?>
                            </div>

                            <div class="schedule-details">
                                <strong><?php echo htmlspecialchars($event["event_title"]); ?></strong>

                                <span>
                                    <?php echo date("g:i A", strtotime($event["event_time"])); ?>
                                    <?php if (!empty($event["location"])): ?>
                                        • <?php echo htmlspecialchars($event["location"]); ?>
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <!-- Forms Section -->
        <div class="dashSection">
            <h2>Completed Forms</h2>
            <table>
                <thead>
                    <tr>
                        <th>Form Title</th>
                        <th>Submitted At</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($form as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row["form_title"]); ?></td>
                            <td><?php echo htmlspecialchars($row["submitted_at"]); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Suggestions Section -->
        <div class="dashSection">
            <h2>My Suggestions</h2>
            <table>
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($suggestions as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row["subject"]); ?></td>
                            <td><?php echo htmlspecialchars($row["message"]); ?></td>
                            <td><?php echo htmlspecialchars($row["submitted_at"]); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Merchandise Section -->
        <div class="dashSection">
            <h2>My Merchandise Orders</h2>
            <?php if (count($merchOrders) === 0): ?>
                <p>You have no merchandise orders yet.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Item</th>
                            <th>Size</th>
                            <th>Quantity</th>
                            <th>Order Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($merchOrders as $order): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order["order_id"]); ?></td>
                                <td><?php echo htmlspecialchars($order["item_name"]); ?></td>
                                <td><?php echo htmlspecialchars($order["size"]); ?></td>
                                <td><?php echo htmlspecialchars($order["quantity"]); ?></td>
                                <td><?php echo htmlspecialchars($order["order_date"]); ?></td>
                                <td><?php echo htmlspecialchars(ucfirst($order["status"])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <div class="dashSection">
            <h2>Academic Resources</h2>
            <?php if (count($accessedResources) === 0): ?>
                <p>You have not accessed any academic resources yet.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Date Accessed</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($accessedResources as $resource): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($resource["resource_title"]); ?></td>
                                <td><?php echo htmlspecialchars($resource["course_code"]); ?></td>
                                <td><?php echo htmlspecialchars($resource["date_accessed"]); ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>