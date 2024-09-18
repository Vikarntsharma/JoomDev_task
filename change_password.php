<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = $_POST['new_password'];
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    $query = $conn->prepare("UPDATE users SET password = ?, last_password_change = NOW() WHERE id = ?");
    $query->execute([$hashed_password, $_SESSION['user_id']]);

    header("Location: user_dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Change Password</h2>
        <form action="change_password.php" method="POST">
            <div class="input-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            <button type="submit" class="btn">Change Password</button>
        </form>
    </div>
</body>
</html>

