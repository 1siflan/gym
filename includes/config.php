<?php
/**
 * includes/config.php
 * 
 * Database connection setup using MySQLi with error handling.
 * Also sets UTF-8 charset and enables exceptions on errors.
 */

declare(strict_types=1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gym";

// Create connection with MySQLi
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // You can log this error as well
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to UTF-8 for proper encoding
$conn->set_charset("utf8mb4");

// Optionally, you can set error reporting to exceptions (PHP 8+)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
