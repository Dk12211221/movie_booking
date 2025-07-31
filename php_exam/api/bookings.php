<?php
require '../db.php';
header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $res = $conn->query("SELECT * FROM bookings WHERE booking_id = $id");
            echo json_encode($res->fetch_assoc());
        } else {
            $res = $conn->query("SELECT * FROM bookings");
            $data = [];
            while ($row = $res->fetch_assoc()) $data[] = $row;
            echo json_encode($data);
        }
        break;

    case 'POST':
        $user_id, $show_id, $booking_time = $input['user_id']; $input['show_id']; $input['booking_time'];
        $stmt = $conn->prepare("INSERT INTO bookings (user_id, show_id, booking_time) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $user_id, $show_id, $booking_time);
        $stmt->execute();
        echo json_encode(["message" => "Bookings created"]);
        break;

    case 'PUT':
        $id = $_GET['id'];
        $user_id, $show_id, $booking_time = $input['user_id']; $input['show_id']; $input['booking_time'];
        $stmt = $conn->prepare("UPDATE bookings SET user_id=?, show_id=?, booking_time=? WHERE booking_id=?");
        $stmt->bind_param("sssi", $user_id, $show_id, $booking_time, $id);
        $stmt->execute();
        echo json_encode(["message" => "Bookings updated"]);
        break;

    case 'DELETE':
        $id = $_GET['id'];
        $conn->query("DELETE FROM bookings WHERE booking_id = $id");
        echo json_encode(["message" => "Bookings deleted"]);
        break;
}
?>
