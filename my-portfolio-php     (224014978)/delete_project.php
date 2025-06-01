<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_projects.php");
    exit;
}

$project_id = intval($_GET['id']);

// First, get the image path so we can delete the image file if needed
$stmt = $conn->prepare("SELECT image FROM projects WHERE id = ?");
$stmt->bind_param("i", $project_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $project = $result->fetch_assoc();

    // Delete the image file from the server (optional)
    if ($project['image'] && file_exists($project['image'])) {
        unlink($project['image']); // Deletes the file
    }

    // Delete the project from the database
    $stmt = $conn->prepare("DELETE FROM projects WHERE id = ?");
    $stmt->bind_param("i", $project_id);
    $stmt->execute();
}

$stmt->close();
$conn->close();

header("Location: manage_projects.php");
exit;
?>