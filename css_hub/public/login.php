<?php
/*
 * Name: Amanda Gbe
 * Date: March 29, 2026
 * Description: Login page for CSS web hub. Validates user credentials and starts a session on successful login. 
 */
session_start();
require_once '../includes/db.php';


$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $error = 'Please enter both email and password.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && hash('sha256', $password) === $user['password_hash']) {
            $_SESSION['email'] = $user['email'];
            $_SESSION['first_name'] = $user['first_name'];
            header('Location: index.php');
            exit;
        } else {
            $error = 'Invalid email or password.';
        }
    }
}

require_once '../includes/header.php';
?>
<div class="auth-container">

    <!-- LEFT SIDE -->
    <div class="auth-left">
        <h2>Welcome to CSS Hub</h2>
        <p>
            Your gateway to all things Computer Science at McMaster.
            Please log in to access your personalized dashboard,
            course materials, and community resources.
        </p>
        <img src="../images/slide2.png" class="auth-illustration">
    </div>

    <!-- RIGHT SIDE -->
    <div class="auth-right">
        <div class="auth-card">
            <h1>Login</h1>

            <?php if ($error): ?>
                <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <form method="POST" action="login.php">
                <div class="form-group">
                    <label for="email">McMaster Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-actions">
                    <button type="submit">Login</button>
                    <p>Don't have an account? 
                        <a href="register.php">Create Account</a>
                    </p>
                </div>
            </form>
        </div>
    </div>

</div>

<?php require_once '../includes/footer.php'; ?>