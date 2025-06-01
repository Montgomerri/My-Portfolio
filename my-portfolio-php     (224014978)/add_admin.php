<?php
session_start();
include 'db.php';

// Only allow super admin
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'super') {
    header("Location: login.php");
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $new_username = trim($_POST['username']);
    $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $new_role = $_POST['role'];

    $sql = "INSERT INTO admins (username, password, role) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $new_username, $new_password, $new_role);

    if ($stmt->execute()) {
        $message = "New admin created successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Admin</title>
</head>
<body>
    <h2>Add New Admin</h2>
    <?php if ($message): ?>
        <p style="color: green;"><?php echo $message; ?></p>
    <?php endif; ?>
    <form method="POST" action="add_admin.php">
        <label>Username:<br>
            <input type="text" name="username" required>
        </label><br><br>
        <label>Password:<br>
            <input type="password" name="password" required>
        </label><br><br>
        <label>Role:<br>
            <select name="role">
                <option value="admin">Admin</option>
                <option value="super">Super Admin</option>
            </select>
        </label><br><br>
        <button type="submit">Create Admin</button>
    </form>

    <p><a href="dashboard.php">Back to Dashboard</a></p>
</body>
</html>