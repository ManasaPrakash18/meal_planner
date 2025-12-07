<?php
// api/db.php - create $conn for API usage
// Do not display PHP errors directly to clients
ini_set('display_errors', 0);
error_reporting(0);

// Database credentials - adjust as needed for your environment
$DB_HOST = '127.0.0.1';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'meal_planner';

// Create connection
$conn = @new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if (!$conn || $conn->connect_errno) {
    // Send minimal JSON error and exit
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'DB connection failed']);
    exit;
}

$conn->set_charset('utf8mb4');

?>
