<?php
// Database credentials
$host = 'localhost';  // Usually 'localhost' if you're running on a local server
$dbname = 'joomdev_task';  // The name of the database
$username = 'root';  // Your database username (replace with actual username)
$password = '';  // Your database password (replace with actual password)

try {
    // Create a new PDO instance
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Set PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Optional: Set the default fetch mode to associative array
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Optional: Use UTF-8 for proper encoding support
    $conn->exec("SET NAMES 'utf8'");
    
    // You can uncomment this line for debugging to confirm connection
    // echo "Connected successfully"; 
} catch (PDOException $e) {
    // Handle connection error
    echo "Connection failed: " . $e->getMessage();
}
?>
