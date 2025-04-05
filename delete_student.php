<?php
header("Content-Type: application/json");

$host = "localhost";
$user = "root";
$pass = "";
$db = "student_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$roll = (int) $data["roll_number"];

if (!$roll) {
    echo json_encode(["error" => "Invalid roll number"]);
    exit;
}

$stmt = $conn->prepare("DELETE FROM students WHERE roll_number = ?");
$stmt->bind_param("i", $roll);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["error" => "Delete failed"]);
}

$conn->close();
?>
