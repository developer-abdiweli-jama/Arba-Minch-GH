<?php
// Database configuration
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root1');
define('DB_PASSWORD', '123456'); // Use quotes for the password
define('DB_NAME', 'hospital_db');

// Attempt to connect to MySQL database
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>