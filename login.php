<?php
require 'config.php'; // Load database configuration
require 'functions.php'; // Load utility functions

session_start(); // Start the session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    if (!$username || !$password) {
        die("Invalid input. Please try again.");
    }

    try {
        // Establish a connection to MySQL/MariaDB using PDO from config.php
        $db = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bindParam(1, $username, PDO::PARAM_STR);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(); // Regenerate session ID
            $_SESSION['user_id'] = $user['uid']; // Ensure 'uid' is the correct column name
            $_SESSION['username'] = $user['username'];
            
            header("Location: dashboard.php"); // Redirect to a secure page
            exit;
        } else {
            echo "Invalid username or password.";
        }
    } catch (PDOException $e) {
        error_log("Login error: " . $e->getMessage()); // Log error for admin review
        echo "An error occurred. Please try again later.";
    }
}
?>

