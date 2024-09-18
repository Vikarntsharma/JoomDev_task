<?php
session_start();
include 'db.php'; // Ensure this path is correct

// Initialize error messages for each field
$errors = [
    'first_name' => '',
    'last_name' => '',
    'email' => '',
    'phone' => '',
    'password' => ''
];

// Check if admin is logged in
if (!$conn) {
    die('Database connection failed.');
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Redirect to admin login if not logged in
    header('Location: admin_login.php');
    exit();
}

// Initialize the auto_generate variable
$auto_generate = isset($_POST['auto_generate']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);

    // Validate inputs
    $valid = true;

    if (empty($first_name)) {
        $errors['first_name'] = "First name is required.";
        $valid = false;
    }

    if (empty($last_name)) {
        $errors['last_name'] = "Last name is required.";
        $valid = false;
    }

    if (empty($email)) {
        $errors['email'] = "Email is required.";
        $valid = false;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
        $valid = false;
    } else {
        // Check if email already exists
        $query = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $query->execute([$email]);
        $email_exists = $query->fetchColumn() > 0;

        if ($email_exists) {
            $errors['email'] = "The email address is already in use.";
            $valid = false;
        }
    }

    if (empty($phone)) {
        $errors['phone'] = "Phone number is required.";
        $valid = false;
    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) { // Basic validation for 10-digit phone number
        $errors['phone'] = "Invalid phone number format. It should be 10 digits.";
        $valid = false;
    }

    if (empty($password)) {
        $errors['password'] = "Password is required.";
        $valid = false;
    } elseif (strlen($password) < 6) { // Password length validation
        $errors['password'] = "Password must be at least 6 characters long.";
        $valid = false;
    }

    if ($valid) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $md5_password = md5($password);

        try {
            $query = $conn->prepare("INSERT INTO users (first_name, last_name, email, phone, password, md5_password) VALUES (?, ?, ?, ?, ?, ?)");
            $query->execute([$first_name, $last_name, $email, $phone, $hashed_password, $md5_password]);
            header('Location: list_users.php');
            exit();
        } catch (PDOException $e) {
            $errors['general'] = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Create Users</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #007bff;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .logout-btn {
            background-color: #f44336;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
        }
        .logout-btn:hover {
            background-color: #e53935;
        }
        .container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            color: white;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            margin-bottom: 20px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .error-message {
            color: red;
            margin-bottom: 20px;
        }
        .input-group {
            margin-bottom: 15px;
        }
        .input-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .input-group input,
        .input-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .input-group textarea {
            height: 100px;
            resize: vertical;
        }
        .btn-submit {
            background-color: #28a745;
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-submit:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
        <a href="logout.php" class="logout-btn">Logout</a>
    </header>
    <div class="container">
        <a href="list_users.php" class="btn">Go to User List</a>
        <h2>Create Users</h2>
        <?php if (!empty($errors['general'])): ?>
            <div class="error-message"><?php echo htmlspecialchars($errors['general']); ?></div>
        <?php endif; ?>
        <form action="admin_dashboard.php" method="POST">
            <div class="input-group">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($first_name ?? ''); ?>">
                <?php if (!empty($errors['first_name'])): ?>
                    <div class="error-message"><?php echo htmlspecialchars($errors['first_name']); ?></div>
                <?php endif; ?>
            </div>
            <div class="input-group">
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($last_name ?? ''); ?>">
                <?php if (!empty($errors['last_name'])): ?>
                    <div class="error-message"><?php echo htmlspecialchars($errors['last_name']); ?></div>
                <?php endif; ?>
            </div>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>">
                <?php if (!empty($errors['email'])): ?>
                    <div class="error-message"><?php echo htmlspecialchars($errors['email']); ?></div>
                <?php endif; ?>
            </div>
            <div class="input-group">
                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($phone ?? ''); ?>">
                <?php if (!empty($errors['phone'])): ?>
                    <div class="error-message"><?php echo htmlspecialchars($errors['phone']); ?></div>
                <?php endif; ?>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="text" id="password" name="password" value="<?php echo htmlspecialchars($password ?? ''); ?>">
                <?php if (!empty($errors['password'])): ?>
                    <div class="error-message"><?php echo htmlspecialchars($errors['password']); ?></div>
                <?php endif; ?>
                <input type="checkbox" id="auto_generate" name="auto_generate" <?php echo $auto_generate ? 'checked' : ''; ?>> Auto-generate password
            </div>
            <button type="submit" class="btn-submit">Create User</button>
        </form>
    </div>
    <script>
        document.getElementById('auto_generate').addEventListener('change', function() {
            if (this.checked) {
                let passwordField = document.getElementById('password');
                let generatedPassword = generateStrongPassword(8); // 8 characters long password
                passwordField.value = generatedPassword;
                passwordField.setAttribute('readonly', true);
            } else {
                document.getElementById('password').removeAttribute('readonly');
            }
        });

        function generateStrongPassword(length) {
            const uppercase = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            const lowercase = "abcdefghijklmnopqrstuvwxyz";
            const numbers = "0123456789";
            const specialChars = "@#$%&*";

            let password = '';
            password += uppercase[Math.floor(Math.random() * uppercase.length)];
            password += lowercase[Math.floor(Math.random() * lowercase.length)];
            password += numbers[Math.floor(Math.random() * numbers.length)];
            password += specialChars[Math.floor(Math.random() * specialChars.length)];

            const alphanumeric = uppercase + lowercase + numbers;

            for (let i = password.length; i < length; i++) {
                password += alphanumeric[Math.floor(Math.random() * alphanumeric.length)];
            }

            return shuffleString(password);
        }

        function shuffleString(string) {
            const array = string.split('');
            for (let i = array.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [array[i], array[j]] = [array[j], array[i]];
            }
            return array.join('');
        }
    </script>
</body>
</html>
