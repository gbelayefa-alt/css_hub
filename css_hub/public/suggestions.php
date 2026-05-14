<?php
/*
 * Name: Harshini Lakshman
 * Date: March 29, 2026
 * Description: Event suggestions form - submits to handlers/submit_suggestions.php
 */
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}

require_once '../includes/header.php';
?>

<div class="page-wrapper">
    <div class="page-card">
        <h1>Submit Event Suggestions</h1>
        <p class="page-intro">Have an idea for an event? Let us know!</p>

        <form action="../handlers/submit_suggestions.php" method="post" onsubmit="return validateSuggestions()">
            <div class="form-group">
                <label for="subject">Subject:</label>
                <input type="text" id="subject" name="subject" required>
            </div>
            <div class="form-group">
                <label for="message">Message:</label>
               <textarea id="message" name="message" required></textarea>
           </div>
            <div class="error-message" id="suggestionError"></div>
            <div class="form-actions">
                <button type="submit">Submit</button>
            </div>
        </form>
        </div>
    </div>

<script src="../js/validation.js"></script>

<?php require_once '../includes/footer.php'; ?>