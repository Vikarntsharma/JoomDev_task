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
$tasks_per_page = 5; // Number of tasks per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $tasks_per_page;

// Fetch total number of tasks
$total_tasks_query = $conn->query("SELECT COUNT(*) FROM tasks");
$total_tasks = $total_tasks_query->fetchColumn();
$total_pages = ceil($total_tasks / $tasks_per_page);

// Fetch tasks for the current page
$tasks_query = $conn->prepare("SELECT * FROM tasks ORDER BY start_time DESC LIMIT :offset, :limit");
$tasks_query->bindValue(':offset', $offset, PDO::PARAM_INT);
$tasks_query->bindValue(':limit', $tasks_per_page, PDO::PARAM_INT);
$tasks_query->execute();
$tasks = $tasks_query->fetchAll(PDO::FETCH_ASSOC);

// Start serial number based on the current page
$serial_no = $offset + 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Tasks</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
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
        header h1 {
            margin: 0;
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
    </style>
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
        <a href="logout.php" class="logout-btn">Logout</a>
    </header>
    <div class="container">
        <a href="export_tasks.php" class="btn">Download CSV</a> <!-- Button for CSV download -->
        <h2>List of Tasks</h2>
        <table>
            <thead>
                <tr>
                    <th>Serial No.</th>
                    <th>Start Time</th>
                    <th>Stop Time</th>
                    <th>Notes</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tasks as $task): ?>
                    <tr>
                        <td><?php echo $serial_no++; ?></td> <!-- Serial number -->
                        <td><?php echo htmlspecialchars($task['start_time']); ?></td>
                        <td><?php echo htmlspecialchars($task['stop_time']); ?></td>
                        <td><?php echo htmlspecialchars($task['notes']); ?></td>
                        <td><?php echo htmlspecialchars($task['description']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination controls -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="list_task.php?page=<?php echo $page - 1; ?>">&laquo; Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="list_task.php?page=<?php echo $i; ?>" class="<?php echo $i == $page ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="list_task.php?page=<?php echo $page + 1; ?>">Next &raquo;</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
