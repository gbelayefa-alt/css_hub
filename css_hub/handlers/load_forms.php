<?php
/*
 * Name: Harshini Lakshman
 * Date: April 17th, 2026
 * Description: Loads form questions once a form is chosen.
 */
session_start();
require_once '../includes/db.php';

$form_id = filter_input(INPUT_GET, "form_id", FILTER_VALIDATE_INT);

$stmt = $pdo->prepare("SELECT * FROM forms WHERE form_id = ?");
$stmt->execute([$form_id]);
$form = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM form_questions WHERE form_id = ? ORDER BY question_order");
$stmt->execute([$form_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2><?php echo htmlspecialchars($form["form_title"]); ?></h2>
<p><?php echo htmlspecialchars($form["form_description"]); ?></p>

<form action="../handlers/form_submit.php" method="post" onsubmit="return validateForm()">
    <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">
    <?php foreach ($questions as $q): ?>
        <div class="form-group">
            <label><?php echo htmlspecialchars($q["question_text"]); ?></label>
            <input type="text" name="answers[<?php echo $q['question_id']; ?>]" required>
        </div>
    <?php endforeach; ?>
    
    <div class="error-message" id="formError"></div>
    
    <div class="form-actions">
        <button type="submit">Submit</button>
    </div>
</form>