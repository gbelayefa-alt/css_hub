<?php 
/*
 * Name: Amanda Gbe
 * Date: April 18, 2026
 * Description: Contact page for logged-in users to submit help messages.
 */
session_start();
require_once '../includes/header.php';

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
?>

<?php if (isset($_GET['success'])): ?>
    <div class="success-message">Your message has been sent successfully!</div>
<?php endif; ?>

<div class="page-wrapper"> 
    <div class="page-card"> 
        <h1>Contact Us</h1>
        <p class="page-intro">Need any help or have questions? Send us a message.</p>
        
        <form method="POST" action="../handlers/submit_help.php"> 
            <div class="form-group">
                <label>Subject:</label>
                <input type="text" name="subject" required>
            </div>

            <div class="form-group">
                <label>Message:</label>
                <textarea name="message" rows="5" required></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn">Send Message</button>
            </div>
        </form>

        <hr style="margin: 30px 0;">

        <h2>Other Ways to Reach Us</h2>
        <div class="contact-links">
            <a href="https://www.instagram.com/mcmastercss/" target="_blank">Instagram</a>
            <a href="https://linktr.ee/mcmastercss" target="_blank">CSS Linktree</a>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>