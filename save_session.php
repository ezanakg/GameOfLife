<?php
// save_session.php
session_start();
require_once "db.php";

// Only allow if user is logged in
if (!isset($_SESSION["user_id"])) {
    die("Not logged in");
}

// Collect data from JavaScript
$user_id = $_SESSION["user_id"];
$generations = intval($_POST["generations"]);
$end_time = date('Y-m-d H:i:s');

$stmt = $conn->prepare("INSERT INTO game_sessions (user_id, end_time, generations) VALUES (?, ?, ?)");
$stmt->bind_param("isi", $user_id, $end_time, $generations);

if ($stmt->execute()) {
    echo "Session saved successfully!";
} else {
    echo "Error saving session.";
}
$stmt->close();
$conn->close();
?>
