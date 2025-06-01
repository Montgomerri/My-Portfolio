<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <p>This is the admin dashboard.</p>

    <ul>
        <?php if ($_SESSION['role'] === 'super') : ?>
            <li><a href="add_admin.php">➕ Add New Admin</a></li>
        <?php endif; ?>

        <!-- These will be linked later -->
        <li><a href="project_manage.php">📁 Manage Projects</a></li>
        <li><a href="about_manage.php">👤 Manage About</a></li>
        <li><a href="messages.php">📬 View Contact Messages</a></li>
        <li><a href="skills_manage.php">🛠 Manage Skills</a></li>
        <li><a href="edit_about.php">📋 Edit About Section</a></li>
        <li><a href="manage_messages.php">📬 Manage Contact Messages</a></li>
    </ul>

    <p><a href="logout.php">🔒 Log Out</a></p>
</body>
</html>