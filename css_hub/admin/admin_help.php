<?php
/*
 * Name: Amanda Gbe
 * Date: April 18, 2026
 * Description: Admin page for viewing help messages submitted by users through the contact form. 
 *      Only accessible to the admin email specified in the code. 
 *      Displays messages in a table format with email, subject, message content, and submission date.
 */
session_start();
require_once '../includes/db.php';
require_once '../includes/header.php';

$admin_email = 'gbea@mcmaster.ca'; // Change to your admin email
if (!isset($_SESSION['email']) || $_SESSION['email'] != $admin_email) {
    header('Location: ../public/login.php');
    exit;
}

$stmt = $pdo->query("SELECT email, subject, message, submitted_at FROM help_messages ORDER BY submitted_at DESC");
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="page-wrapper"> 
    <div class="page-card"> 
        <h1>Admin – Help Messages</h1>
        <p>View messages submitted by users through the contact form.</p>

        <?php if (count($messages) == 0): ?>
            <p>No help messages found.</p>
        <?php else: ?>
            <table class="simple-table">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Submitted At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($messages as $msg): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($msg['email']); ?></td>
                            <td><?php echo htmlspecialchars($msg['subject']); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($msg['message'])); ?></td>
                            <td><?php echo htmlspecialchars($msg['submitted_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>