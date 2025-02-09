<?php
require 'db_connection.php'; // Adjusted path
require '../config/config.php'; // Adjusted path
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        echo "Login successful! Redirecting..."; // Debugging
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];
    
        switch ($user['role']) {
            case 'admin':
                header("Location: admin/dashboard.php");
                break;
            case 'doctor':
                header("Location: doctor/dashboard.php");
                break;
            case 'patient':
                header("Location: patient/dashboard.php");
                break;
            default:
                header("Location: login.php");
                break;
        }
        exit();
    } else {
        die("Invalid email or password.");
    }
} else {
    echo "Form not submitted.";
}
?>