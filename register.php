<?php
require 'config.php'; // Database configuration with $conn defined
require 'functions.php'; // Utility functions (e.g., for password hashing)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize user inputs
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    if (!$username || !$email || !$password) {
        http_response_code(400); // Bad Request
        die("Invalid input. All fields are required.");
    }

    // Hash the password securely using bcrypt
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // Store user data in the MySQL/MariaDB database
    try {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password_hash); // Prevent SQL injection

        if ($stmt->execute()) {
            echo "Registration successful! You can now log in.";
        } else {
            throw new Exception("Registration failed due to a database error."); // Throw an exception if execution fails
        }

        $stmt->close(); // Always close prepared statements
    } catch (mysqli_sql_exception $e) {
        if ($conn->errno === 1062) { // Handle duplicate entry
            http_response_code(409); // Conflict
            echo "Error: Username or email already exists.";
        } else {
            error_log("Database error: " . $e->getMessage()); // Log error for admin
            http_response_code(500); // Internal Server Error
            echo "An unexpected error occurred. Please try again later.";
        }
    } finally {
        $conn->close(); // Close the database connection
    }
}
?>