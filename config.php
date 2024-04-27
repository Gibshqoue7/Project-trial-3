<?php
// MySQL/MariaDB connection settings
$servername = "localhost"; // Change if needed
$username = "root"; // Database username
$password = ""; // Database password (ensure this is correctly set)
$database = "aproject"; // Name of your database

// Create a MySQLi connection
$conn = new mysqli($servername, $username, $password, $database);

// Check for connection errors
if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error); // Log error to a secure place
    die("Connection failed. Please try again later."); // Generic error message for users
}
?>
