<?php
require '../db.php';
header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $res = $conn->query("SELECT * FROM booking_seats WHERE seat_id = $id");
            echo json_encode($res->fetch_assoc());
        } else {
            $res = $conn->query("SELECT * FROM booking_seats");
            $data = [];
            while ($row = $res->fetch_assoc()) {
                $data[] = $row;
            }
            echo json_encode($data);
        }
        break;

    case 'POST':
        $booking_id = $input['booking_id'];
        $seat_number = $input['seat_number'];

        $stmt = $conn->prepare("INSERT INTO booking_seats (booking_id, seat_number) VALUES (?, ?)");
        $stmt->bind_param("is", $booking_id, $seat_number);
        if ($stmt->execute()) {
            echo json_encode(["message" => "Seat booked successfully"]);
        } else {
            echo json_encode(["error" => "Insert failed"]);
        }
        break;

    case 'PUT':
        $id = $_GET['id'];
        $booking_id = $input['booking_id'];
        $seat_number = $input['seat_number'];

        $stmt = $conn->prepare("UPDATE booking_seats SET booking_id = ?, seat_number = ? WHERE seat_id = ?");
        $stmt->bind_param("isi", $booking_id, $seat_number, $id);
        if ($stmt->execute()) {
            echo json_encode(["message" => "Seat updated"]);
        } else {
            echo json_encode(["error" => "Update failed"]);
        }
        break;

    case 'DELETE':
        $id = $_GET['id'];
        if ($conn->query("DELETE FROM booking_seats WHERE seat_id = $id")) {
            echo json_encode(["message" => "Seat deleted"]);
        } else {
            echo json_encode(["error" => "Delete failed"]);
        }
        break;

    default:
        echo json_encode(["error" => "Invalid request"]);
        break;
}
?>
