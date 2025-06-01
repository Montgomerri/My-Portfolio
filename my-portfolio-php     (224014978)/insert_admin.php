<?php
include 'db.php';

// Only run this once!
$username = "superadmin";
$password = password_hash("admin123", PASSWORD_DEFAULT); // Encrypt the password
$role = "super";

$sql = "INSERT INTO admins (username, password, role) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $username, $password, $role);

if ($stmt->execute()) {
    echo "Super admin account created!";
} else {
    echo "Error: " . $conn->error;
}

$stmt->close();
$conn->close();
?>