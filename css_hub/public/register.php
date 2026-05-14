<?php
/*
 * Name: Amanda Gbe
 * Date: April 17th, 2026 (fixed form action)
 * Description: Create account page for CSS web hub. Validates user input and creates a new user session on successful registration. 
 */
session_start();
require '../includes/header.php';

if (isset($_SESSION['errors'])) {
    echo '<div class="error-message">';
    foreach ($_SESSION['errors'] as $error) {
        echo '<p>' . htmlspecialchars($error) . '</p>';
    }
    echo '</div>';
    unset($_SESSION['errors']);  //clear after showing
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - CSS Web Hub</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="auth-container">
        <!--LEFT SIDE-->
        <div class="auth-left">
            <h2>Create Account</h2>
            <p>
                Join the CSS Hub community to access events,
                resources, and exclusive opportunites
            </p>
            <img src="../images/slide5.png" class="auth-illustration">
        </div>

        <!--RIGHT SIDE-->
        <div class="auth-right">
            <div class="auth-card">
                <h2>Create Your Account</h2>

                <form method="POST" action="../handlers/process_register.php">
                    <input type="text" name="first_name" placeholder="First Name" required>
                    <input type="text" name="last_name" placeholder="Last Name" required>
                    <input type="email" name="email" placeholder="McMaster Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                    <button type="submit">Create Account</button>
                </form>
                <p>Already have an account? <a href="login.php">Login here</a>.</p>
            </div>
        </div>
    </div>
</body>

</html>
<?php require_once '../includes/footer.php'; ?>