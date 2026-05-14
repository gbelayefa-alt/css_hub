<?php
/*
 * Name: Daniel Yu
 * Date: March 25, 2026
 * Description: Academic resources page - shows resources stored in the database.
 */
session_start();
require_once '../includes/db.php';
require_once '../includes/header.php';

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}

$stmt = $pdo->query("SELECT resource_id, resource_title, course_code, file_link FROM resources ORDER BY course_code, resource_title");
$resources = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Academic Resources</h1>
<p>Access notes, slides, and study materials for your courses.</p>

<ul class="resource-list" id="resource-list">
    <?php foreach ($resources as $res): ?>
        <li data-resource-id="<?= $res['resource_id'] ?>">
            <span class="resource-title">
                <?= htmlspecialchars($res['course_code']) ?> – <?= htmlspecialchars($res['resource_title']) ?>
            </span>
            <a href="#" class="resource-link" data-file-link="<?= htmlspecialchars($res['file_link']) ?>">View</a>
        </li>
    <?php endforeach; ?>
</ul>

<script>
    document.querySelectorAll('.resource-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const resourceId = this.closest('li').getAttribute('data-resource-id');
            const fileLink = this.getAttribute('data-file-link');
            fetch('../ajax/track_resource.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'resource_id=' + encodeURIComponent(resourceId)
            }).then(() => {
                if (fileLink && fileLink !== '#') window.open(fileLink, '_blank');
                else alert('Resource link not available yet.');
            }).catch(err => console.error('Tracking failed:', err));
        });
    });
</script>

<?php require_once '../includes/footer.php'; ?>