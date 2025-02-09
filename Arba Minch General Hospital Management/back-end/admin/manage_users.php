<?php
// Start the session
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../front-end/login.html');
    exit();
}

// Include the database connection
require_once '../../config/config.php';
require_once '../../back-end/db_connection.php';

// Handle form submissions (Create, Update, Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_user'])) {
        // Add a new user
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
        $email = $_POST['email'];
        $role = $_POST['role'];

        $stmt = $pdo->prepare("INSERT INTO Users (username, password, email, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $password, $email, $role]);
    } elseif (isset($_POST['edit_user'])) {
        // Update an existing user
        $user_id = $_POST['user_id'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $role = $_POST['role'];

        $stmt = $pdo->prepare("UPDATE Users SET username = ?, email = ?, role = ? WHERE user_id = ?");
        $stmt->execute([$username, $email, $role, $user_id]);
    } elseif (isset($_POST['delete_user'])) {
        // Delete a user
        $user_id = $_POST['user_id'];

        $stmt = $pdo->prepare("DELETE FROM Users WHERE user_id = ?");
        $stmt->execute([$user_id]);
    }
}

// Fetch all users
$stmt = $pdo->query("SELECT * FROM Users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="../../assets/Css/tailwind.css">
</head>
<body class="bg-gray-100">
    <!-- Admin Header -->
    <header class="bg-blue-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">Manage Users</h1>
            <nav>
                <a href="dashboard.php" class="mx-2 hover:text-blue-300">Dashboard</a>
                <a href="manage_doctors.php" class="mx-2 hover:text-blue-300">Manage Doctors</a>
                <a href="manage_patients.php" class="mx-2 hover:text-blue-300">Manage Patients</a>
                <a href="manage_appointments.php" class="mx-2 hover:text-blue-300">Manage Appointments</a>
                <a href="manage_pharmacy.php" class="mx-2 hover:text-blue-300">Manage Pharmacy</a>
                <a href="manage_billing.php" class="mx-2 hover:text-blue-300">Manage Billing</a>
                <a href="../../back-end/logout.php" class="mx-2 hover:text-blue-300">Logout</a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <section class="container mx-auto my-8">
        <!-- Add User Form -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <h2 class="text-2xl font-bold mb-4">Add New User</h2>
            <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="text" name="username" placeholder="Username" required class="p-2 border rounded">
                <input type="password" name="password" placeholder="Password" required class="p-2 border rounded">
                <input type="email" name="email" placeholder="Email" required class="p-2 border rounded">
                <select name="role" required class="p-2 border rounded">
                    <option value="admin">Admin</option>
                    <option value="doctor">Doctor</option>
                    <option value="patient">Patient</option>
                </select>
                <button type="submit" name="add_user" class="bg-blue-600 text-white p-2 rounded">Add User</button>
            </form>
        </div>

        <!-- Users Table -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-4">User List</h2>
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th class="text-left">ID</th>
                        <th class="text-left">Username</th>
                        <th class="text-left">Email</th>
                        <th class="text-left">Role</th>
                        <th class="text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['user_id']; ?></td>
                            <td><?php echo $user['username']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td><?php echo $user['role']; ?></td>
                            <td>
                                <!-- Edit Form -->
                                <form method="POST" class="inline">
                                    <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                    <input type="text" name="username" value="<?php echo $user['username']; ?>" required class="p-1 border rounded">
                                    <input type="email" name="email" value="<?php echo $user['email']; ?>" required class="p-1 border rounded">
                                    <select name="role" required class="p-1 border rounded">
                                        <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                        <option value="doctor" <?php echo $user['role'] === 'doctor' ? 'selected' : ''; ?>>Doctor</option>
                                        <option value="patient" <?php echo $user['role'] === 'patient' ? 'selected' : ''; ?>>Patient</option>
                                    </select>
                                    <button type="submit" name="edit_user" class="bg-green-600 text-white p-1 rounded">Update</button>
                                </form>
                                <!-- Delete Form -->
                                <form method="POST" class="inline">
                                    <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                    <button type="submit" name="delete_user" class="bg-red-600 text-white p-1 rounded">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</body>
</html>