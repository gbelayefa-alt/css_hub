<?php
/*
 * Name: Daniel Yu
 * Date: April 16, 2026
 * Description: Software tools guide – links to XAMPP, VS Code, and GitLab resources.
 */
session_start();
require_once '../includes/db.php';
require_once '../includes/header.php';
?>

<h1>Software Tools Guide</h1>
<p>Below are links to setup guides and documentation for essential development tools.</p>

<ul class="resource-list">
    <li>
        <span class="resource-title">XAMPP (Local Web Server)</span>
        <a href="https://www.apachefriends.org/faq_windows.html" class="resource-link" target="_blank">View Guide</a>
    </li>
    <li>
        <span class="resource-title">Visual Studio Code (Code Editor)</span>
        <a href="https://code.visualstudio.com/docs/setup/setup-overview" class="resource-link" target="_blank">View Guide</a>
    </li>
    <li>
        <span class="resource-title">GitLab</span>
        <a href="../uploads/gitlab_guide.pdf" class="resource-link" target="_blank">Download PDF</a>
    </li>
</ul>

<p><a href="resources.php" class="home-link">← Back to Academic Resources</a></p>

<?php require_once '../includes/footer.php'; ?>