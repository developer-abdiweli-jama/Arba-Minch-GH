<?php
$host = 'localhost';
$dbname = 'hospital_db';
$username = 'root1';
$password = '123456'; // Use quotes for the password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>