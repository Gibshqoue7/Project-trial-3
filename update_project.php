<?php
require 'config.php'; // Include database configuration from a separate file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pid = intval($_POST['pid']); // Securely get project ID
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $phase = htmlspecialchars($_POST['phase']);

    // Validate date formats (example for YYYY-MM-DD)
    $date_format = 'Y-m-d';
    $start_dt = DateTime::createFromFormat($date_format, $start_date);
    $end_dt = DateTime::createFromFormat($date_format, $end_date);
    if (!$start_dt || !$end_dt || $start_dt->format($date_format) !== $start_date || $end_dt->format($date_format) !== $end_date) {
        echo "Invalid date format.";
        exit;
    }

    try {
        // Connect to MySQL/MariaDB using PDO from configuration
        $db = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception handling

        // Prepare the SQL query with placeholders for parameterized queries
        $stmt = $db->prepare("UPDATE projects SET title = ?, description = ?, start_date = ?, end_date = ?, phase = ? WHERE id = ?");

        // Bind the parameters to the query
        $stmt->bindParam(1, $title, PDO::PARAM_STR);
        $stmt->bindParam(2, $description, PDO::PARAM_STR);
        $stmt->bindParam(3, $start_date, PDO::PARAM_STR);
        $stmt->bindParam(4, $end_date, PDO::PARAM_STR);
        $stmt->bindParam(5, $phase, PDO::PARAM_STR);
        $stmt->bindParam(6, $pid, PDO::PARAM_INT);
        
        $stmt->execute(); // Execute the update query
        
        echo "Project updated successfully!";
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        echo "An error occurred while updating the project. Please try again.";
    }
} else {
    echo "Request method is not supported.";
}
?>
