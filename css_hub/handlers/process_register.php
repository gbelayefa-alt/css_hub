<?php
/*
 * Name: Amanda Gbe
 * Date: April 19, 2026
 * Description: Processes registration form submission. 
 *  Validates input, checks for existing email, hashes password with SHA256, and inserts new user into database.
 *  Redirects with error messages if validation fails or on success.
 */
session_start();
require_once '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirmPassword = trim($_POST['confirm_password'] ?? '');

    $errors = [];

    if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($confirmPassword)) {
        $errors[] = 'Please fill in all fields.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    if (!preg_match('/^[a-zA-Z0-9._%+-]+@mcmaster\.ca$/', $email)) {
        $errors[] = 'Please use a valid McMaster email address.';
    }

    if ($password !== $confirmPassword) {
        $errors[] = 'Passwords do not match.';
    }
    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header('Location: ../public/register.php');
        exit;
    }

    // Check if email already exists
    try {
        $stmt = $pdo->prepare("SELECT email FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);

        if ($stmt->fetch()) {
            $_SESSION['errors'] = ['Email is already registered.'];
            header('Location: ../public/register.php');
            exit;
        }

        // Use SHA256 to match login.php (hash('sha256', $password))
        $passwordHash = hash('sha256', $password);
        $mac_id = explode('@', $email)[0];

        // Ensure mac_id is unique
        $base_mac = $mac_id;
        $counter = 1;
        while (true) {
            $stmt = $pdo->prepare("SELECT mac_id FROM users WHERE mac_id = ?");
            $stmt->execute([$mac_id]);
            if (!$stmt->fetch()) break;
            $mac_id = $base_mac . $counter;
            $counter++;
        }

        // INSERT USER
        $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password_hash, mac_id) 
            VALUES (:first_name, :last_name, :email, :password_hash, :mac_id)");
        $stmt->execute([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'password_hash' => $passwordHash,
            'mac_id' => $mac_id
        ]);

        $_SESSION['email'] = $email;
        $_SESSION['first_name'] = $firstName;
        header('Location: ../public/index.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['errors'] = ['An error occurred. Please try again later.'];
        header('Location: ../public/register.php');
        exit;
    }
} else {
    header('Location: ../public/register.php');
    exit;
}
