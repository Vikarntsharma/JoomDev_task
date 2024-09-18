<?php
session_start();
include 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Redirect to admin login if not logged in
    header('Location: admin_login.php');
    exit();
}

// Pagination settings
$users_per_page = 5; // Number of users per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $users_per_page;

// Fetch total number of users
$total_users_query = $conn->query("SELECT COUNT(*) FROM users");
$total_users = $total_users_query->fetchColumn();
$total_pages = ceil($total_users / $users_per_page);

// Fetch users for the current page
$users_query = $conn->prepare("SELECT * FROM users LIMIT :offset, :limit");
$users_query->bindValue(':offset', $offset, PDO::PARAM_INT);
$users_query->bindValue(':limit', $users_per_page, PDO::PARAM_INT);
$users_query->execute();
$users = $users_query->fetchAll(PDO::FETCH_ASSOC);

// Start serial number based on the current page
$serial_no = $offset + 1;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Users</title>
    <style>
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        thead {
            background-color: #f4f4f4;
        }

        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination a {
            display: inline-block;
            padding: 8px 16px;
            margin: 0 4px;
            border: 1px solid #ddd;
            border-radius: 4px;
            color: #333;
            text-decoration: none;
        }

        .pagination a.active {
            background-color: #4CAF50;
            color: white;
        }

        .pagination a:hover {
            background-color: #ddd;
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
        }

        .btn:hover {
            background-color: #45a049;
        }

        /* Logout Button Wrapper */
        .logout-wrapper {
            text-align: right; /* Aligns content to the right */
            margin: 10px 20px; /* Optional: adds margin around the button */
        }

        /* Logout Button Style */
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

    </style>
</head>
<body>
    <div class="container">
        <h2>List of Users</h2>
        <div class="logout-wrapper">
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
        <a href="list_task.php" class="btn">Go to Task List</a>
        <table>
            <thead>
                <tr>
                    <th>Serial No.</th>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $serial_no++; ?></td> <!-- Serial number -->
                        <td><?php echo htmlspecialchars($user['first_name']); ?></td>
                        <td><?php echo htmlspecialchars($user['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['phone']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination controls -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="list_users.php?page=<?php echo $page - 1; ?>">&laquo; Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="list_users.php?page=<?php echo $i; ?>" class="<?php echo $i == $page ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="list_users.php?page=<?php echo $page + 1; ?>">Next &raquo;</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
