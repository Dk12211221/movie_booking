<?php
// Database connection file

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "movie_booking";

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode([
        "error" => "Database connection failed",
        "details" => $conn->connect_error
    ]));
}
?>
