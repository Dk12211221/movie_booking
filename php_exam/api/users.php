<?php
require '../db.php';
header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $res = $conn->query("SELECT * FROM users WHERE user_id = $id");
            echo json_encode($res->fetch_assoc());
        } else {
            $res = $conn->query("SELECT * FROM users");
            $data = [];
            while ($row = $res->fetch_assoc()) {
                $data[] = $row;
            }
            echo json_encode($data);
        }
        break;

    case 'POST':
        if (!$input || !isset($input['name']) || !isset($input['email'])) {
            echo json_encode(["error" => "Missing name or email in request body"]);
            http_response_code(400);
            exit;
        }

        $name = $input['name'];
        $email = $input['email'];

        $stmt = $conn->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $email);




        if ($stmt->execute()) {
            echo json_encode(["message" => "User created successfully"]);
        } else {
            echo json_encode(["error" => "Insert failed"]);
        }
        break;

    case 'PUT':
        $id = $_GET['id'];
        $name = $input['name'];
        $email = $input['email'];

        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE user_id = ?");
        $stmt->bind_param("ssi", $name, $email, $id);

        if ($stmt->execute()) {
            echo json_encode(["message" => "User updated"]);
        } else {
            echo json_encode(["error" => "Update failed"]);
        }
        break;

    case 'DELETE':
        $id = $_GET['id'];
        if ($conn->query("DELETE FROM users WHERE user_id = $id")) {
            echo json_encode(["message" => "User deleted"]);
        } else {
            echo json_encode(["error" => "Delete failed"]);
        }
        break;

    default:
        echo json_encode(["error" => "Invalid request"]);
        break;
}
?>
