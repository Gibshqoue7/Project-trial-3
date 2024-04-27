<?php
require 'config.php'; // Assuming $servername, $username, $password, and $database are defined here

if (isset($_GET['pid'])) {
    $pid = intval($_GET['pid']); // Securely get project ID from the query parameter

    try {
        // Connect to MySQL/MariaDB using PDO from the config file
        $db = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exceptions for error handling

        // Prepare an SQL query with a parameterized placeholder
        $stmt = $db->prepare("SELECT * FROM projects WHERE pid = ?");
        
        // Bind the project ID to the query
        $stmt->bindParam(1, $pid, PDO::PARAM_INT); // Bind project ID as an integer
        
        $stmt->execute(); // Execute the query

        // Fetch the first row as an associative array
        $row = $stmt->fetch(PDO::FETCH_ASSOC); 
        
        if ($row) {
            // Return the project details as JSON or a formatted response
            echo json_encode($row); // Send data as JSON
        } else {
            echo "Project not found.";
        }
        
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage()); // Log database errors
        echo "An error occurred while fetching the project details.";
    }
} else {
    echo "No project ID provided.";
}
?>
