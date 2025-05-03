<?php
require_once "db.php";

$username = "admin";
$password = "admin123"; // plain password

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO admin_users (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $hashed_password);

if ($stmt->execute()) {
    echo "✅ Admin user created successfully!";
} else {
    echo "❌ Failed to create admin user.";
}

$stmt->close();
$conn->close();
?>
