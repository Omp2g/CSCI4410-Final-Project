<?php
require 'db.php';

header('Content-Type: application/json');

// ✅ CORS headers for cross-origin requests from React
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Authorization, Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");

// ✅ Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit();
}

// ✅ Read and validate incoming JSON data
$data = json_decode(file_get_contents('php://input'), true);

$name = trim($data['name'] ?? '');
$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';
$role = $data['role'] ?? '';

// ✅ Basic validation
if (empty($name) || empty($email) || empty($password) || empty($role)) {
  http_response_code(400);
  echo json_encode(['message' => 'All fields are required.']);
  exit();
}

// ✅ Check if user already exists
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->rowCount() > 0) {
  http_response_code(409); // Conflict
  echo json_encode(['message' => 'User already exists.']);
  exit();
}

// ✅ Hash the password
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// ✅ Insert the new user into the database
$insert = $pdo->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)");
$success = $insert->execute([$name, $email, $hashedPassword, $role]);

if ($success) {
  echo json_encode(['message' => 'Registration successful']);
} else {
  http_response_code(500);
  echo json_encode(['message' => 'Something went wrong while registering']);
}
?>

