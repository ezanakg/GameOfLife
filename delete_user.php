<?php
session_start();
require_once "db.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

header("Location: admin_dashboard.php");
exit();
?>
