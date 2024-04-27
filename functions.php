<?php
function get_db_connection() {
    $servername = "localhost";
    $username = "root";
    $password = ""; // Adjusted for potential empty password
    $database = "aproject";

    try {
        $db = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch (PDOException $e) {
        error_log("Database connection failed: " . $e->getMessage());
        return null;
    }
}

function hash_password($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

function verify_password($password, $hashed_password) {
    return password_verify($password, $hashed_password);
}

function generate_csrf_token() {
    return bin2hex(random_bytes(32));
}

function sanitize_input($input, $type = 'string') {
    switch ($type) {
        case 'email':
            return filter_var($input, FILTER_SANITIZE_EMAIL);
        case 'int':
            return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
        default:
            return filter_var($input, FILTER_SANITIZE_STRING);
    }
}

function is_user_logged_in() {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    return isset($_SESSION['user_id']);
}

function logout_user() {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    session_unset();
    session_destroy();
}
?>