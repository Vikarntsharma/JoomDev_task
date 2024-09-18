<?php
session_start();
include 'db.php';

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: user_login.php');
    exit();
}

$error_message = ''; // Variable to store error messages

// Handle task submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tasks = $_POST['tasks'];
    $user_id = $_SESSION['user_id'];

    // Validate each task
    foreach ($tasks as $task) {
        $start_time = trim($task['start_time']);
        $stop_time = trim($task['stop_time']);
        $notes = trim($task['notes']);
        $description = trim($task['description']);

        if (empty($start_time) || empty($stop_time) || empty($notes) || empty($description)) {
            $error_message = 'All fields are required for each task.';
            break;
        }

        // If validation passes, insert the task into the database
        if (empty($error_message)) {
            $query = $conn->prepare("INSERT INTO tasks (user_id, start_time, stop_time, notes, description) VALUES (?, ?, ?, ?, ?)");
            $query->execute([$user_id, $start_time, $stop_time, $notes, $description]);
        }
    }

    if (empty($error_message)) {
        $_SESSION['success_message'] = 'Tasks submitted successfully!';
        header('Location: user_dashboard.php');
        exit();
    }
}

// Retrieve success message if available
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
// Clear the success message from session
unset($_SESSION['success_message']);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .logout-wrapper {
            text-align: right;
            margin: 10px 20px;
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

        .success-message {
            color: green;
            margin-bottom: 20px;
        }

        .error-message {
            color: red;
            margin-bottom: 20px;
        }

        .task-group {
            margin-bottom: 20px;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
            position: relative;
        }

        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .btn:hover {
            background-color: #45a049;
        }

        .add-task-btn, .remove-task-btn {
            background-color: #2196F3;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
        }

        .add-task-btn:hover {
            background-color: #1E88E5;
        }

        .remove-task-btn {
            background-color: #f44336;
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .remove-task-btn:hover {
            background-color: #e53935;
        }
    </style>

    <script>
        let taskCount = 1; // Start with 1 task

        // Function to add a new task
        function addTask() {
            taskCount++;
            const taskGroup = document.createElement('div');
            taskGroup.classList.add('task-group');
            taskGroup.setAttribute('id', 'task-group-' + taskCount);

            taskGroup.innerHTML = `
                <h3>Task ${taskCount}</h3>
                <div class="input-group">
                    <label for="start_time_${taskCount}">Start Time</label>
                    <input type="datetime-local" id="start_time_${taskCount}" name="tasks[${taskCount - 1}][start_time]">
                </div>
                <div class="input-group">
                    <label for="stop_time_${taskCount}">Stop Time</label>
                    <input type="datetime-local" id="stop_time_${taskCount}" name="tasks[${taskCount - 1}][stop_time]">
                </div>
                <div class="input-group">
                    <label for="notes_${taskCount}">Notes</label>
                    <textarea id="notes_${taskCount}" name="tasks[${taskCount - 1}][notes]"></textarea>
                </div>
                <div class="input-group">
                    <label for="description_${taskCount}">Description</label>
                    <textarea id="description_${taskCount}" name="tasks[${taskCount - 1}][description]"></textarea>
                </div>
                <button type="button" class="remove-task-btn" onclick="removeTask(${taskCount})">Remove Task</button>
            `;

            document.getElementById('tasks-container').appendChild(taskGroup);
        }

        // Function to remove a task
        function removeTask(taskId) {
            const taskGroup = document.getElementById('task-group-' + taskId);
            if (taskGroup) {
                taskGroup.remove();
            }
        }
    </script>
</head>
<body>
    <div class="logout-wrapper">
        <a href="user_logout.php" class="logout-btn">Logout</a>
    </div>
    <div class="container">
        <h2>User Dashboard - Submit Tasks</h2>

        <?php if (!empty($success_message)): ?>
            <div class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <form action="user_dashboard.php" method="POST">
            <div id="tasks-container">
                <div class="task-group" id="task-group-1">
                    <h3>Task 1</h3>
                    <div class="input-group">
                        <label for="start_time_1">Start Time</label>
                        <input type="datetime-local" id="start_time_1" name="tasks[0][start_time]">
                    </div>
                    <div class="input-group">
                        <label for="stop_time_1">Stop Time</label>
                        <input type="datetime-local" id="stop_time_1" name="tasks[0][stop_time]">
                    </div>
                    <div class="input-group">
                        <label for="notes_1">Notes</label>
                        <textarea id="notes_1" name="tasks[0][notes]"></textarea>
                    </div>
                    <div class="input-group">
                        <label for="description_1">Description</label>
                        <textarea id="description_1" name="tasks[0][description]"></textarea>
                    </div>
                </div>
            </div>

            <button type="button" class="add-task-btn" onclick="addTask()">Add Task</button>
            <button type="submit" class="btn">Submit Tasks</button>
        </form>
    </div>
</body>
</html>

