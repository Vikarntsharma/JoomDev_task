<?php
include 'db.php'; // Include database connection

session_start(); // Start session to manage login state

// Check if the admin is already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    // Redirect to admin dashboard if already logged in
    header('Location: admin_dashboard.php');
    exit();
}

$error = ""; // Initialize an error message variable

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the admin username and password from the POST request
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate if fields are not empty
    if (empty($username)) {
        $error = "Username is required!";
    } elseif (empty($password)) {
        $error = "Password is required!";
    } else {
        // Prepare the SQL statement to select the admin user by username
        $query = $conn->prepare("SELECT * FROM admin WHERE username = ?");
        $query->execute([$username]);
        
        // Fetch the result (it should return a single row or false)
        $admin = $query->fetch();

        // If admin user is found and password is verified
        if ($admin && password_verify($password, $admin['password'])) {
            // Set session or redirect to the admin dashboard
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $admin['username'];
            
            // Redirect to admin dashboard
            header('Location: admin_dashboard.php');
            exit();
        } else {
            // If credentials are invalid, show an error message
            $error = "Invalid username or password!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Login</h2>
        
        <!-- Show error message if login fails or fields are missing -->
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form action="admin_login.php" method="POST">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username">
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password">
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
    </div>
</body>
</html>
