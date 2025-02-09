<?php
session_start();
require 'db_connection.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];

    // Sanitize and Validate Full Name
    $name = trim(htmlspecialchars($_POST['name']));
    if (empty($name)) {
        $errors[] = "Full Name is required.";
    }

    // Sanitize and Validate Email
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Validate Password
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    if (empty($password) || strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // Validate Role
    $role = htmlspecialchars($_POST['role']);
    if (empty($role)) {
        $errors[] = "Role is required.";
    }

    // If errors exist, return them
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: ../front-end/register.html");
        exit();
    }

    // Hash the password securely
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    try {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $_SESSION['errors'] = ["Email already exists. Please use a different email."];
            header("Location: ../front-end/register.html");
            exit();
        }

        // Insert new user into database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $hashed_password, $role]);

        // Redirect to login page
        $_SESSION['success'] = "Registration successful! Please log in.";
        header("Location: ../front-end/login.html");
        exit();
    } catch (PDOException $e) {
        $_SESSION['errors'] = ["Database error: " . $e->getMessage()];
        header("Location: ../front-end/register.html");
        exit();
    }
}
?>