<?php
session_start();
require_once "db.php";

// Only allow if admin logged in
if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch all users
$result = $conn->query("SELECT id, username, created_at FROM users");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Admin Dashboard</h2>
    <a href="admin_logout.php" class="btn btn-danger mb-3">Logout</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
                <td>
                    <a href="delete_user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
