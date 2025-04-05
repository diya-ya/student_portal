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
$name = trim($data["name"]);
$course = trim($data["course"]);
$email = trim($data["email"]);

if (!$roll || !$name || !$course || !$email) {
    echo json_encode(["error" => "All fields are required"]);
    exit;
}

$stmt = $conn->prepare("UPDATE students SET name = ?, course = ?, email = ? WHERE roll_number = ?");
$stmt->bind_param("sssi", $name, $course, $email, $roll);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["error" => "Update failed"]);
}

$conn->close();
?>
