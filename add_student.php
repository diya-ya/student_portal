
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");

// Connect to DB
$host = "localhost";
$user = "root";
$pass = "";
$db = "student_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

// Get raw JSON
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// Validate input
if (!isset($data['roll_number'], $data['name'], $data['course'], $data['email'])) {
    echo json_encode(["error" => "Incomplete input"]);
    exit;
}

$roll = (int) $data['roll_number'];
$name = trim($data['name']);
$course = trim($data['course']);
$email = trim($data['email']);

// Check if student already exists
$check = $conn->prepare("SELECT * FROM students WHERE roll_number = ?");
$check->bind_param("i", $roll);
$check->execute();
$checkResult = $check->get_result();

if ($checkResult->num_rows > 0) {
    echo json_encode(["error" => "Student already exists"]);
    exit;
}

// Insert student
$stmt = $conn->prepare("INSERT INTO students (roll_number, name, course, email) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isss", $roll, $name, $course, $email);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["error" => "Failed to insert"]);
}

$conn->close();
?>
