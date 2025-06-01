<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch all skills
$sql = "SELECT * FROM skills ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Skills</title>
</head>
<body>
    <h1>Manage Skills / Services</h1>
    <a href="add_skill.php">➕ Add New Skill</a>
    <table border="1" cellpadding="8" cellspacing="0" style="margin-top:20px;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Skill Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td>
                            <a href="edit_skill.php?id=<?php echo $row['id']; ?>">✏ Edit</a> |
                            <a href="delete_skill.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this skill?');">🗑 Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4">No skills found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <p><a href="dashboard.php">⬅ Back to Dashboard</a></p>
</body>
</html>