<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: manage_skills.php");
    exit;
}

$id = $_GET['id'];

// Fetch the skill
$stmt = $conn->prepare("SELECT * FROM skills WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$skill = $result->fetch_assoc();

if (!$skill) {
    echo "Skill not found.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $level = trim($_POST['level']);

    $update = $conn->prepare("UPDATE skills SET name = ?, level = ? WHERE id = ?");
    $update->bind_param("ssi", $name, $level, $id);

    if ($update->execute()) {
        header("Location: manage_skills.php");
        exit;
    } else {
        echo "Error updating skill.";
    }
}
?>

<h2>Edit Skill / Service</h2>
<form method="POST">
    <label>Name:</label><br>
    <input type="text" name="name" value="<?php echo htmlspecialchars($skill['name']); ?>" required><br><br>

    <label>Level/Description:</label><br>
    <input type="text" name="level" value="<?php echo htmlspecialchars($skill['level']); ?>" required><br><br>

    <button type="submit">Update</button>
</form>
<p><a href="manage_skills.php">⬅ Back</a></p>