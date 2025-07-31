<?php
require 'db.php';
header("Content-Type: application/json");

$table = $_GET['table'] ?? null;
$id = $_GET['id'] ?? null;
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);


$tables = [
    'users' => ['pk' => 'user_id', 'cols' => ['name', 'email']],
    'movies' => ['pk' => 'movie_id', 'cols' => ['title', 'genre', 'duration']],
    'theaters' => ['pk' => 'theater_id', 'cols' => ['name', 'location']],
    'shows' => ['pk' => 'show_id', 'cols' => ['movie_id', 'theater_id', 'show_time']],
    'bookings' => ['pk' => 'booking_id', 'cols' => ['user_id', 'show_id', 'booking_time']],
    'booking_seats' => ['pk' => 'seat_id', 'cols' => ['booking_id', 'seat_number']],
];

if (!$table || !array_key_exists($table, $tables)) {
    echo json_encode(["error" => "Invalid or missing table parameter"]);
    exit;
}

$pk = $tables[$table]['pk'];
$cols = $tables[$table]['cols'];

switch ($method) {
    case 'GET':
        if ($id) {
            $res = $conn->query("SELECT * FROM $table WHERE $pk = $id");
            echo json_encode($res->fetch_assoc());
        } else {
            $res = $conn->query("SELECT * FROM $table");
            $data = [];
            while ($row = $res->fetch_assoc()) $data[] = $row;
            echo json_encode($data);
        }
        break;

    case 'POST':
        $values = array_map(fn($col) => $input[$col], $cols);
        $placeholders = implode(', ', array_fill(0, count($cols), '?'));
        $stmt = $conn->prepare("INSERT INTO $table (" . implode(', ', $cols) . ") VALUES ($placeholders)");
        $types = str_repeat('s', count($cols)); 
        $stmt->bind_param($types, ...$values);
        $stmt->execute();
        echo json_encode(["message" => "$table record created"]);
        break;

    case 'PUT':
        if (!$id) {
            echo json_encode(["error" => "Missing ID for update"]);
            break;
        }
        $values = array_map(fn($col) => $input[$col], $cols);
        $setStr = implode(' = ?, ', $cols) . ' = ?';
        $stmt = $conn->prepare("UPDATE $table SET $setStr WHERE $pk = ?");
        $types = str_repeat('s', count($cols)) . 'i';
        $stmt->bind_param($types, ...$values, $id);
        $stmt->execute();
        echo json_encode(["message" => "$table record updated"]);
        break;

    case 'DELETE':
        if (!$id) {
            echo json_encode(["error" => "Missing ID for deletion"]);
            break;
        }
        $conn->query("DELETE FROM $table WHERE $pk = $id");
        echo json_encode(["message" => "$table record deleted"]);
        break;

    default:
        echo json_encode(["error" => "Unsupported method"]);
}
?>
