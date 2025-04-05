<?php
header("Content-Type: application/json");

// Database connection
$host = "localhost";
$user = "root";  // Default XAMPP user
$pass = "";      // Default password is empty
$db = "student_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed"]));
}

// Get roll number
$roll_number = isset($_GET['roll_number']) ? (int)$_GET['roll_number'] : 0;

$sql = "SELECT * FROM students WHERE roll_number = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $roll_number);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode(["error" => "No student found with this roll number"]);
}

$conn->close();
?>
