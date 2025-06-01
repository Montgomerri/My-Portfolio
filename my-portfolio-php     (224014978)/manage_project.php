<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch projects from DB
$sql = "SELECT * FROM projects ORDER BY created_at DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Manage Projects</title>
</head>
<body>
    <h1>Manage Projects</h1>
    <a href="add_project.php">➕ Add New Project</a>
    <table border="1" cellpadding="8" cellspacing="0" style="margin-top:20px;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Technologies</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result && $result->num_rows > 0) : ?>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td><?php echo htmlspecialchars($row['technologies']); ?></td>
                    <td>
                        <a href="edit_project.php?id=<?php echo $row['id']; ?>">✏ Edit</a> |
                        <a href="delete_project.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this project?');">🗑 Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else : ?>
            <tr><td colspan="5">No projects found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <p><a href="dashboard.php">⬅ Back to Dashboard</a></p>
</body>
</html>