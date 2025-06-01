<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $technologies = $_POST['technologies'] ?? '';

    // Check if image was uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = basename($_FILES['image']['name']);
        $uploadDir = 'uploads/';

        // Create uploads folder if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $uploadFilePath = $uploadDir . $fileName;

        if (move_uploaded_file($fileTmpPath, $uploadFilePath)) {
            // Insert project into DB
            $stmt = $conn->prepare("INSERT INTO projects (title, description, image, technologies) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $title, $description, $uploadFilePath, $technologies);

            if ($stmt->execute()) {
                $success = "Project added successfully!";
            } else {
                $error = "Database error: " . $conn->error;
            }
            $stmt->close();
        } else {
            $error = "Failed to move uploaded file.";
        }
    } else {
        $error = "Please upload an image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Add New Project</title>
</head>
<body>
    <h1>Add New Project</h1>

    <?php if ($error) : ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if ($success) : ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php endif; ?>

    <form action="add_project.php" method="post" enctype="multipart/form-data">
        <label>Title:<br>
            <input type="text" name="title" required>
        </label><br><br>

        <label>Description:<br>
            <textarea name="description" rows="5" required></textarea>
        </label><br><br>

        <label>Technologies:<br>
            <input type="text" name="technologies" placeholder="e.g. HTML, CSS, PHP" required>
        </label><br><br>

        <label>Project Image:<br>
            <input type="file" name="image" accept="image/*" required>
        </label><br><br>

        <button type="submit">Add Project</button>
    </form>

    <p><a href="manage_projects.php">⬅ Back to Projects</a></p>
</body>
</html>