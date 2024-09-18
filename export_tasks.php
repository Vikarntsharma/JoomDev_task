<?php
session_start();
include 'db.php';

// Set the headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=tasks_report.csv');

// Open the output stream for writing
$output = fopen('php://output', 'w');

// Write the header row
fputcsv($output, ['ID', 'User Name', 'Start Time', 'Stop Time', 'Notes', 'Description']);

// Fetch all tasks along with user names from the database
$tasks_query = $conn->query("
    SELECT t.id, u.first_name AS user_name, t.start_time, t.stop_time, t.notes, t.description
    FROM tasks t
    JOIN users u ON t.user_id = u.id
    ORDER BY t.start_time DESC
");
$tasks = $tasks_query->fetchAll(PDO::FETCH_ASSOC);

// Write each task as a row in the CSV
foreach ($tasks as $task) {
    fputcsv($output, [
        $task['id'],
        $task['user_name'],
        $task['start_time'],
        $task['stop_time'],
        $task['notes'],
        $task['description']
    ]);
}

// Close the output stream
fclose($output);
exit();
