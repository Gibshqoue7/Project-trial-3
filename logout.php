<?php
session_start(); // Start the session

// Database connection information for MySQL
$servername = "localhost"; // Change if needed
$username = "root"; // Database username
$password = ""; // Database password (set correctly)
$database = "aproject"; // Name of your database

// Establish a database connection using PDO
try {
    $db = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exceptions

    // Optional: Record the logout event or update user status
    if (isset($_SESSION['uid'])) {
        $uid = $_SESSION['uid'];
        $action = 'logout'; // Define the action being recorded

        $stmt = $db->prepare("INSERT INTO log (uid, action) VALUES (?, ?)");
        $stmt->bindParam(1, $uid, PDO::PARAM_INT); // Bind user ID as an integer
        $stmt->bindParam(2, $action, PDO::PARAM_STR); // Bind action as a string

        $stmt->execute(); // Execute the query to record the logout event
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage()); // Log any errors
}

// Clear all session variables and destroy the session to log out the user
session_unset(); // Clear all session data
session_destroy(); // Destroy the session

// Redirect the user to the login page or a landing page after logging out
header("Location: login_page.html"); // Redirect to the login page or a suitable page
exit(); // Ensure no further code is executed after redirect
?>
