<?php
// Database configuration file to get connection details
require 'config.php'; 

header('Content-Type: application/json'); // Set the header to application/json

try {
    // Connect to MySQL/MariaDB using PDO from configuration variables
    $db = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception handling

    // Fetch all projects from the "projects" table
    $result = $db->query("SELECT * FROM projects");

    // Initialize an empty array to collect projects
    $projects = [];

    // Loop through the results and fetch as associative arrays
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $projects[] = $row; // Add each project to the array
    }

    // Return the projects as JSON
    echo json_encode($projects);
    
} catch (PDOException $e) {
    // Log database errors and provide a generic error message
    error_log("Database error: " . $e->getMessage());
    echo json_encode(["error" => "An error occurred while fetching the projects."]);
}
?>
