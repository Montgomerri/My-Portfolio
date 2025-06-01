<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch about info (only one row expected)
$about = [
    'bio' => '',
    'experience' => '',
    'education' => '',
    'skills' => ''
];

$sql = "SELECT * FROM about LIMIT 1";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    $about = $result->fetch_assoc();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bio = $_POST['bio'];
    $experience = $_POST['experience'];
    $education = $_POST['education'];
    $skills = $_POST['skills'];

    if ($result && $result->num_rows > 0) {
        // Update
        $stmt = $conn->prepare("UPDATE about SET bio=?, experience=?, education=?, skills=? WHERE id=?");
        $stmt->bind_param("ssssi", $bio, $experience, $education, $skills, $about['id']);
    } else {
        // Insert
        $stmt = $conn->prepare("INSERT INTO about (bio, experience, education, skills) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $bio, $experience, $education, $skills);
    }

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Edit About Section</title>
</head>
<body>
    <h1>Edit About Section</h1>
    <form method="post">
        <label>Bio:</label><br>
        <textarea name="bio" rows="4" cols="50"><?php echo htmlspecialchars($about['bio']); ?></textarea><br><br>

        <label>Experience:</label><br>
        <textarea name="experience" rows="4" cols="50"><?php echo htmlspecialchars($about['experience']); ?></textarea><br><br>

        <label>Education:</label><br>
        <textarea name="education" rows="4" cols="50"><?php echo htmlspecialchars($about['education']); ?></textarea><br><br>

        <label>Skills:</label><br>
        <textarea name="skills" rows="4" cols="50"><?php echo htmlspecialchars($about['skills']); ?></textarea><br><br>

        <button type="submit">💾 Save</button>
    </form>

    <p><a href="dashboard.php">⬅ Back to Dashboard</a></p>
</body>
</html>