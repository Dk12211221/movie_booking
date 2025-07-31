<?php
require '../db.php';
header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $res = $conn->query("SELECT * FROM theaters WHERE theater_id = $id");
            echo json_encode($res->fetch_assoc());
        } else {
            $res = $conn->query("SELECT * FROM theaters");
            $data = [];
            while ($row = $res->fetch_assoc()) $data[] = $row;
            echo json_encode($data);
        }
        break;

    case 'POST':
        $name, $location = $input["name"]; $input["location"];
        $stmt = $conn->prepare("INSERT INTO theaters (name, location) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $location);
        $stmt->execute();
        echo json_encode(["message" => "Theaters created"]);
        break;

    case 'PUT':
        $id = $_GET['id'];
        $name, $location = $input['name']; $input['location'];
        $stmt = $conn->prepare("UPDATE theaters SET name=?, location=? WHERE theater_id=?");
        $stmt->bind_param("ssi", $name, $location, $id);
        $stmt->execute();
        echo json_encode(["message" => "Theaters updated"]);
        break;

    case 'DELETE':
        $id = $_GET['id'];
        $conn->query("DELETE FROM theaters WHERE theater_id = $id");
        echo json_encode(["message" => "Theaters deleted"]);
        break;
}
?>
