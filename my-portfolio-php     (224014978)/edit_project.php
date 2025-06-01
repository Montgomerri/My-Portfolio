<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$error = '';
$success = '';

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_projects.php");
    exit;
}

$project_id = intval($_GET['id']);

// Fetch existing project data
$stmt = $conn->prepare("SELECT * FROM projects WHERE id = ?");
$stmt->bind_param("i", $project_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    header("Location: manage_projects.php");
    exit;
}

$project = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $technologies = $_POST['technologies'] ?? '';
    $imagePath = $project['image']; // Keep old image by default

    // Check if a new image was uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = basename($_FILES['image']['name']);
        $uploadDir = 'uploads/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $uploadFilePath = $uploadDir . $fileName;

        if (move_uploaded_file($fileTmpPath, $uploadFilePath)) {
            $imagePath = $uploadFilePath;
        } else {
            $error = "Failed to move uploaded file.";
        }
    }

    if (!$error) {
        $stmt = $conn->prepare("UPDATE projects SET title = ?, description = ?, image = ?, technologies = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $title, $description, $imagePath, $technologies, $project_id);

        if ($stmt->execute()) {
            $success = "Project updated successfully!";
            // Refresh project data after update
            $project['title'] = $title;
            $project['description'] = $description;
            $project['technologies'] = $technologies;
            $project['image'] = $imagePath;
        } else {
            $error = "Database error: " . $conn->error;
        }
        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Edit Project</title>
</head>
<body>
    <h1>Edit Project</h1>

    <?php if ($error) : ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if ($success) : ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php endif; ?>

    <form action="edit_project.php?id=<?php echo $project_id; ?>" method="post" enctype="multipart/form-data">
        <label>Title:<br>
            <input type="text" name="title" required value="<?php echo htmlspecialchars($project['title']); ?>">
        </label><br><br>

        <label>Description:<br>
            <textarea name="description" rows="5" required><?php echo htmlspecialchars($project['description']); ?></textarea>
        </label><br><br>

        <label>Technologies:<br>
            <input type="text" name="technologies" required value="<?php echo htmlspecialchars($project['technologies']); ?>">
        </label><br><br>

        <label>Current Image:<br>
            <?php if ($project['image'] && file_exists($project['image'])): ?>
                <img src="<?php echo $project['image']; ?>" alt="Project Image" style="max-width: 200px;"><br>
            <?php else: ?>
                No image uploaded.
            <?php endif; ?>
        </label><br>

        <label>Replace Image:<br>
            <input type="file" name="image" accept="image/*">
        </label><br><br>

        <button type="submit">Update Project</button>
    </form>

    <p><a href="manage_projects.php">⬅ Back to Projects</a></p>
</body>
</html>