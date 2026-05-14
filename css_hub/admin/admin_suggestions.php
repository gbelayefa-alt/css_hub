<?php
/*
 * Name: Daniel Yu
 * Date: April 19, 2026
 * Description: Admin panel to view and delete all event suggestions.
 */
session_start();
require_once '../includes/db.php';
require_once '../includes/header.php';

$admin_email = 'gbea@mcmaster.ca';
if (!isset($_SESSION['email']) || $_SESSION['email'] != $admin_email) {
    header('Location: ../public/login.php');
    exit;
}

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = filter_input(INPUT_POST, 'delete_id', FILTER_VALIDATE_INT);
    if ($delete_id) {
        $stmt = $pdo->prepare("DELETE FROM event_suggestions WHERE suggestion_id = ?");
        $stmt->execute([$delete_id]);
    }
    header('Location: admin_suggestions.php');
    exit;
}

$stmt = $pdo->query("SELECT * FROM event_suggestions ORDER BY submitted_at DESC");
$suggestions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Admin – All Suggestions</h1>
<p>View and delete user suggestions.</p>

<table class="simple-table">
    <thead>
        <tr><th>ID</th><th>Email</th><th>Subject</th><th>Message</th><th>Submitted</th><th>Action</th></tr>
    </thead>
    <tbody>
        <?php foreach ($suggestions as $s): ?>
            <tr>
                <td><?= $s['suggestion_id'] ?></td>
                <td><?= htmlspecialchars($s['email']) ?></td>
                <td><?= htmlspecialchars($s['subject']) ?></td>
                <td><?= htmlspecialchars($s['message']) ?></td>
                <td><?= $s['submitted_at'] ?></td>
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="delete_id" value="<?= $s['suggestion_id'] ?>">
                        <button type="button" onclick="openDeleteModal(this.form)">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div id="deleteModal" class="modal">
    <div class="modal-content">
        <p>Are you sure you want to delete this suggestion?</p>
        <button onclick="confirmDelete()">Yes</button>
        <button onclick="closeDeleteModal()">Cancel</button>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>