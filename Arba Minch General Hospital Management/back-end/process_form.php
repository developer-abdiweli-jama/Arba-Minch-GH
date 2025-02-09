<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to sanitize input data
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to validate email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Function to validate phone number (basic validation)
function validatePhone($phone) {
    return preg_match("/^[0-9]{10,15}$/", $phone);
}

// Function to send email
function sendEmail($to, $subject, $message, $headers) {
    return mail($to, $subject, $message, $headers);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Determine which form is being submitted
    if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['date']) && isset($_POST['doctor'])) {
        // Process Appointment Form
        $name = sanitizeInput($_POST['name']);
        $email = sanitizeInput($_POST['email']);
        $phone = sanitizeInput($_POST['phone']);
        $date = sanitizeInput($_POST['date']);
        $doctor = sanitizeInput($_POST['doctor']);
        $message = isset($_POST['message']) ? sanitizeInput($_POST['message']) : '';

        // Validate inputs
        if (empty($name) || empty($email) || empty($phone) || empty($date) || empty($doctor)) {
            echo "All fields are required.";
            exit;
        }

        if (!validateEmail($email)) {
            echo "Invalid email address.";
            exit;
        }

        if (!validatePhone($phone)) {
            echo "Invalid phone number.";
            exit;
        }

        // Prepare email content
        $to = "arbaminchgh@moh.et";
        $subject = "New Appointment Request";
        $email_message = "Name: $name\n";
        $email_message .= "Email: $email\n";
        $email_message .= "Phone: $phone\n";
        $email_message .= "Appointment Date: $date\n";
        $email_message .= "Selected Doctor: $doctor\n";
        $email_message .= "Message: $message\n";

        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";

        // Send email
        if (sendEmail($to, $subject, $email_message, $headers)) {
            echo "Appointment request submitted successfully!";
        } else {
            echo "Failed to submit appointment request. Please try again later.";
        }
    } elseif (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['message'])) {
        // Process Contact Form
        $name = sanitizeInput($_POST['name']);
        $email = sanitizeInput($_POST['email']);
        $message = sanitizeInput($_POST['message']);

        // Validate inputs
        if (empty($name) || empty($email) || empty($message)) {
            echo "All fields are required.";
            exit;
        }

        if (!validateEmail($email)) {
            echo "Invalid email address.";
            exit;
        }

        // Prepare email content
        $to = "arbaminchgh@moh.et";
        $subject = "New Contact Form Submission";
        $email_message = "Name: $name\n";
        $email_message .= "Email: $email\n";
        $email_message .= "Message: $message\n";

        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";

        // Send email
        if (sendEmail($to, $subject, $email_message, $headers)) {
            echo "Message sent successfully!";
        } else {
            echo "Failed to send message. Please try again later.";
        }
    } else {
        echo "Invalid form submission.";
    }
} else {
    echo "Invalid request method.";
}
?>