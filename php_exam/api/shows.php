<?php
require '../db.php';
header("Content-Type: application/json");

error_reporting(E_ALL);
ini_set('display_errors', 1);

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $res = $conn->query("SELECT * FROM shows WHERE show_id = $id");
            echo json_encode($res->fetch_assoc());
        } else {
            $res = $conn->query("SELECT * FROM shows");
            $data = [];
            while ($row = $res->fetch_assoc()) {
                $data[] = $row;
            }
            echo json_encode($data);
        }
        break;

    case 'POST':
        if (!$input || !isset($input['movie_id']) || !isset($input['theater_id']) || !isset($input['show_time'])) {
            echo json_encode(["error" => "Missing movie_id, theater_id, or show_time"]);
            exit;
        }

        $movie_id = $input['movie_id'];
        $theater_id = $input['theater_id'];
        $show_time = $input['show_time'];

        $stmt = $conn->prepare("INSERT INTO shows (movie_id, theater_id, show_time) VALUES (?, ?, ?)");
        if (!$stmt) {
            echo json_encode(["error" => "Prepare failed", "details" => $conn->error]);
            exit;
        }

        $stmt->bind_param("iis", $movie_id, $theater_id, $show_time);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Show created successfully"]);
        } else {
            echo json_encode(["error" => "Insert failed", "details" => $stmt->error]);
        }
        break;

    case 'PUT':
        if (!isset($_GET['id'])) {
            echo json_encode(["error" => "Missing show ID"]);
            http_response_code(400);
            exit;
        }

        $id = $_GET['id'];
        $movie_id = $input['movie_id'];
        $theater_id = $input['theater_id'];
        $show_time = $input['show_time'];

        $stmt = $conn->prepare("UPDATE shows SET movie_id = ?, theater_id = ?, show_time = ? WHERE show_id = ?");
        $stmt->bind_param("iisi", $movie_id, $theater_id, $show_time, $id);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Show updated"]);
        } else {
            echo json_encode(["error" => "Update failed", "details" => $stmt->error]);
        }
        break;

    case 'DELETE':
        if (!isset($_GET['id'])) {
            echo json_encode(["error" => "Missing show ID"]);
            http_response_code(400);
            exit;
        }

        $id = $_GET['id'];
        if ($conn->query("DELETE FROM shows WHERE show_id = $id")) {
            echo json_encode(["message" => "Show deleted"]);
        } else {
            echo json_encode(["error" => "Delete failed", "details" => $conn->error]);
        }
        break;

    default:
        echo json_encode(["error" => "Invalid request method"]);
        break;
}
?>
