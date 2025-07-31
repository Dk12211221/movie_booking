<?php


$host = "localhost";
$user = "root";
$pass = "";
$dbname = "movie_booking";


error_reporting(E_ALL);
ini_set('display_errors', 1);


$conn = new mysqli($host, $user, $pass, $dbname);


if ($conn->connect_error) {
    die(json_encode([
        "error" => "Database connection failed",
        "details" => $conn->connect_error
    ]));
}
?>
