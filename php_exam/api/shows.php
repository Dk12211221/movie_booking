<?php
require '../db.php';
header("Content-Type: application/json");

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
            while ($row = $res->fetch_assoc()) $data[] = $row;
            echo json_encode($data);
        }
        break;

    case 'POST':
        $movie_id, $theater_id, $show_time = $input['movie_id']; $input['theater_id']; $input['show_time'];
        $stmt = $conn->prepare("INSERT INTO shows (movie_id, theater_id, show_time) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $movie_id, $theater_id, $show_time);
        $stmt->execute();
        echo json_encode(["message" => "Shows created"]);
        break;

    case 'PUT':
        $id = $_GET['id'];
        $movie_id, $theater_id, $show_time = $input['movie_id']; $input['theater_id']; $input['show_time'];
        $stmt = $conn->prepare("UPDATE shows SET movie_id=?, theater_id=?, show_time=? WHERE show_id=?");
        $stmt->bind_param("sssi", $movie_id, $theater_id, $show_time, $id);
        $stmt->execute();
        echo json_encode(["message" => "Shows updated"]);
        break;

    case 'DELETE':
        $id = $_GET['id'];
        $conn->query("DELETE FROM shows WHERE show_id = $id");
        echo json_encode(["message" => "Shows deleted"]);
        break;
}
?>
