<?php
// Start the session
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../front-end/login.html');
    exit();
}

// Include the database connection
require_once '../config/config.php';
require_once '../db_connection.php';

// Test database connection
try {
    $stmt = $pdo->query("SELECT 1");
    echo "Database connection successful!";
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch counts for quick stats
$user_count = 0;
$doctor_count = 0;
$patient_count = 0;
$appointment_count = 0;
$pharmacy_count = 0;
$billing_count = 0;

try {
    // Count total users
    $stmt = $pdo->query("SELECT COUNT(*) AS user_count FROM Users");
    $user_count = $stmt->fetch()['user_count'];

    // Count total doctors
    $stmt = $pdo->query("SELECT COUNT(*) AS doctor_count FROM Doctors");
    $doctor_count = $stmt->fetch()['doctor_count'];

    // Count total patients
    $stmt = $pdo->query("SELECT COUNT(*) AS patient_count FROM Patients");
    $patient_count = $stmt->fetch()['patient_count'];

    // Count total appointments
    $stmt = $pdo->query("SELECT COUNT(*) AS appointment_count FROM Appointments");
    $appointment_count = $stmt->fetch()['appointment_count'];

    // Count total pharmacy records
    $stmt = $pdo->query("SELECT COUNT(*) AS pharmacy_count FROM Pharmacy");
    $pharmacy_count = $stmt->fetch()['pharmacy_count'];

    // Count total billing records
    $stmt = $pdo->query("SELECT COUNT(*) AS billing_count FROM Billing");
    $billing_count = $stmt->fetch()['billing_count'];
} catch (PDOException $e) {
    die("Error fetching data: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/Css/tailwind.css">
</head>
<body class="bg-gray-100">
    <!-- Admin Dashboard Header -->
    <header class="bg-blue-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">Admin Dashboard</h1>
            <nav>
                <a href="../back-end/admin/manage_users.php" class="mx-2 hover:text-blue-300">Manage Users</a>
                <a href="../back-end/admin/manage_doctors.php" class="mx-2 hover:text-blue-300">Manage Doctors</a>
                <a href="../back-end/admin/manage_patients.php" class="mx-2 hover:text-blue-300">Manage Patients</a>
                <a href="../back-end/admin/manage_appointments.php" class="mx-2 hover:text-blue-300">Manage Appointments</a>
                <a href="../back-end/admin/manage_pharmacy.php" class="mx-2 hover:text-blue-300">Manage Pharmacy</a>
                <a href="../back-end/admin/manage_billing.php" class="mx-2 hover:text-blue-300">Manage Billing</a>
                <a href="../../back-end/logout.php" class="mx-2 hover:text-blue-300">Logout</a>
            </nav>
        </div>
    </header>

    <!-- Quick Stats Section -->
    <section class="container mx-auto my-8">
        <h2 class="text-2xl font-bold mb-4">Quick Stats</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Total Users -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-bold">Total Users</h3>
                <p class="text-3xl"><?php echo $user_count; ?></p>
            </div>
            <!-- Total Doctors -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-bold">Total Doctors</h3>
                <p class="text-3xl"><?php echo $doctor_count; ?></p>
            </div>
            <!-- Total Patients -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-bold">Total Patients</h3>
                <p class="text-3xl"><?php echo $patient_count; ?></p>
            </div>
            <!-- Total Appointments -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-bold">Total Appointments</h3>
                <p class="text-3xl"><?php echo $appointment_count; ?></p>
            </div>
            <!-- Total Pharmacy Records -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-bold">Total Pharmacy Records</h3>
                <p class="text-3xl"><?php echo $pharmacy_count; ?></p>
            </div>
            <!-- Total Billing Records -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-bold">Total Billing Records</h3>
                <p class="text-3xl"><?php echo $billing_count; ?></p>
            </div>
        </div>
    </section>

    <!-- Recent Activity Section -->
    <section class="container mx-auto my-8">
        <h2 class="text-2xl font-bold mb-4">Recent Activity</h2>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th class="text-left">User</th>
                        <th class="text-left">Action</th>
                        <th class="text-left">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Example Row -->
                    <tr>
                        <td>Admin</td>
                        <td>Logged in</td>
                        <td>2023-10-01 12:00 PM</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</body>
</html>