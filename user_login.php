<?php
session_start();
include 'db.php';

// Redirect if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: user_dashboard.php');
    exit();
}

$error_message = ''; // Variable to store error messages

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Server-side validation
    if (empty($email)) {
        $error_message = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Invalid email format.';
    } elseif (empty($password)) {
        $error_message = 'Password is required.';
    } else {
        // Proceed if inputs are valid

        // Check if user exists
        $query = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $query->execute([$email]);
        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $storedPassword = $user['password'];
            $isPasswordValid = false;

            // Check if the stored password is hashed using password_hash or MD5
            if (strlen($storedPassword) == 32) {
                // MD5 passwords are 32 characters long, so we verify using MD5
                if (md5($password) === $storedPassword) {
                    $isPasswordValid = true;

                    // After successful MD5 login, rehash the password using password_hash for future logins
                    $newHashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $updatePasswordQuery = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                    $updatePasswordQuery->execute([$newHashedPassword, $user['id']]);
                }
            } else {
                // Password is hashed with password_hash, verify using password_verify
                if (password_verify($password, $storedPassword)) {
                    $isPasswordValid = true;
                }
            }

            // If password is valid, proceed with login
            if ($isPasswordValid) {
                // Check if first time login (last_password_change is NULL) or if 30 days have passed
                $lastPasswordChange = $user['last_password_change'];
                $now = new DateTime();
                $passwordChangeNeeded = false;

                if (is_null($lastPasswordChange)) {
                    // First time login
                    $passwordChangeNeeded = true;
                } else {
                    // Check if 30 days have passed
                    $lastPasswordChangeDate = new DateTime($lastPasswordChange);
                    $interval = $lastPasswordChangeDate->diff($now);
                    if ($interval->days > 30) {
                        $passwordChangeNeeded = true;
                    }
                }

                // Redirect to change password page if needed
                if ($passwordChangeNeeded) {
                    $_SESSION['user_id'] = $user['id'];
                    header('Location: change_password.php');
                    exit();
                }

                // Update last_login timestamp
                $query = $conn->prepare("UPDATE users SET last_login = ? WHERE id = ?");
                $query->execute([$now->format('Y-m-d H:i:s'), $user['id']]);

                // Log the user in and redirect to the dashboard
                $_SESSION['user_id'] = $user['id'];
                header('Location: user_dashboard.php');
                exit();
            } else {
                // Set error message for invalid email or password
                $error_message = 'Invalid email or password!';
            }
        } else {
            // Set error message for invalid email or password
            $error_message = 'Invalid email or password!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .error-message {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>User Login</h2>

        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <form action="user_login.php" method="POST">
            <div class="input-group">
                <label for="email">Email</label>
                <input type="text" id="email" name="email">
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
