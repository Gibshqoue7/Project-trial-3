<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $servername = 'localhost';
    $database = 'aproject';
    $username = 'root';
    $password = ''; // Adjusted password setting

    // Sanitize and validate data more thoroughly
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $start_date = filter_input(INPUT_POST, 'start_date', FILTER_SANITIZE_STRING);
    $end_date = filter_input(INPUT_POST, 'end_date', FILTER_SANITIZE_STRING);
    $phase = filter_input(INPUT_POST, 'phase', FILTER_SANITIZE_STRING);

    // Additional validation for dates
    $date_format = 'Y-m-d'; // Assuming dates are in 'YYYY-MM-DD' format
    if (!DateTime::createFromFormat($date_format, $start_date) || !DateTime::createFromFormat($date_format, $end_date)) {
        die("Invalid date format. Please use YYYY-MM-DD.");
    }

    try {
        $dsn = "mysql:host=$servername;dbname=$database;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
        $db = new PDO($dsn, $username, $password, $options);

        $stmt = $db->prepare("INSERT INTO projects (title, description, start_date, end_date, phase) VALUES (?, ?, ?, ?, ?)");
        $stmt->bindParam(1, $title);
        $stmt->bindParam(2, $description);
        $stmt->bindParam(3, $start_date);
        $stmt->bindParam(4, $end_date);
        $stmt->bindParam(5, $phase);

        $stmt->execute();

        echo "Project added successfully!";
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        echo "An error occurred while adding the project.";
    }
}
?>
