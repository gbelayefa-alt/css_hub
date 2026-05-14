<?php
/*
 * Name: Harshini Lakshman
 * Date: March 29, 2026
 * Description: Displays a form based on form_id from the database.
 */
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}

$stmt = $pdo->query("SELECT form_id, form_title FROM forms ORDER BY form_title");
$forms = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once '../includes/header.php';
?>


<div class="page-wrapper">
    <div class="page-card">
    
        <form method="GET" action="forms.php">
            <h1>Forms</h1>
            <div class="form-group">
                <label for="formSelect">Choose Form:</label>
                <select id="formSelect" name="form_id">
                    <option value="">-- Select Form --</option>

                    <?php foreach ($forms as $form): ?>
                    <option value="<?php echo $form["form_id"]; ?>"
                        <?php if (isset($_GET['form_id']) && $_GET['form_id'] == $form["form_id"]) echo "selected"; ?>>
                        
                        <?php echo htmlspecialchars($form["form_title"]); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            </div>

            <div class="form-actions">
                <button type="submit">Load Form</button>
            </div>
        </form>

        <?php
        $form_id = filter_input(INPUT_GET, "form_id", FILTER_VALIDATE_INT);

        if ($form_id) {

            // get form info
            $stmt = $pdo->prepare("SELECT * FROM forms WHERE form_id = ?");
            $stmt->execute([$form_id]);
            $form = $stmt->fetch(PDO::FETCH_ASSOC);

            // get questions
            $stmt = $pdo->prepare("SELECT * FROM form_questions WHERE form_id = ? ORDER BY question_order");
            $stmt->execute([$form_id]);
            $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        
        <h2><?php echo htmlspecialchars($form["form_title"]); ?></h2>
        <p><?php echo htmlspecialchars($form["form_description"]); ?></p>

        <form action="../handlers/form_submit.php" method="post">
            <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">

            <?php foreach ($questions as $q): ?>
                <div class="form-group">
                    <label><?php echo htmlspecialchars($q["question_text"]); ?></label>
                    <input type="text" name="answers[<?php echo $q['question_id']; ?>]" required>
                </div>
            <?php endforeach; ?>

            <div class="form-actions">
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>
</div>

<?php } ?>

<script src="/css_hub/css_hub/js/validation.js" defer></script>

<?php require_once '../includes/footer.php'; ?>