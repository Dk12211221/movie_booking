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
            $res = $conn->query("SELECT * FROM movies WHERE movie_id = $id");
            echo json_encode($res->fetch_assoc());
        } else {
            $res = $conn->query("SELECT * FROM movies");
            $data = [];
            while ($row = $res->fetch_assoc()) {
                $data[] = $row;
            }
            echo json_encode($data);
        }
        break;

    case 'POST':
        if (!$input || !isset($input['title']) || !isset($input['genre']) || !isset($input['duration'])) {
            echo json_encode(["error" => "Missing title, genre, or duration in request body"]);
            http_response_code(400);
            exit;
        }

        $title = $input['title'];
        $genre = $input['genre'];
        $duration = $input['duration'];

        $stmt = $conn->prepare("INSERT INTO movies (title, genre, duration) VALUES (?, ?, ?)");
        if (!$stmt) {
            echo json_encode(["error" => "Prepare failed", "details" => $conn->error]);
            exit;
        }

        $stmt->bind_param("ssi", $title, $genre, $duration);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Movie created successfully"]);
        } else {
            echo json_encode(["error" => "Insert failed", "details" => $stmt->error]);
        }
        break;

    case 'PUT':
        if (!isset($_GET['id'])) {
            echo json_encode(["error" => "Missing movie ID"]);
            http_response_code(400);
            exit;
        }

        $id = $_GET['id'];
        $title = $input['title'];
        $genre = $input['genre'];
        $duration = $input['duration'];

        $stmt = $conn->prepare("UPDATE movies SET title = ?, genre = ?, duration = ? WHERE movie_id = ?");
        $stmt->bind_param("ssii", $title, $genre, $duration, $id);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Movie updated"]);
        } else {
            echo json_encode(["error" => "Update failed", "details" => $stmt->error]);
        }
        break;

    case 'DELETE':
        if (!isset($_GET['id'])) {
            echo json_encode(["error" => "Missing movie ID"]);
            http_response_code(400);
            exit;
        }

        $id = $_GET['id'];
        if ($conn->query("DELETE FROM movies WHERE movie_id = $id")) {
            echo json_encode(["message" => "Movie deleted"]);
        } else {
            echo json_encode(["error" => "Delete failed", "details" => $conn->error]);
        }
        break;

    default:
        echo json_encode(["error" => "Invalid request method"]);
        break;
}
?>
