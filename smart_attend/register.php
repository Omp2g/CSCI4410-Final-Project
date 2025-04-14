<?php
require 'db.php';

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Authorization, Content-Type");

$data = json_decode(file_get_contents('php://input'), true);

$name = $data['name'];
$email = $data['email'];
$password = $data['password'];
$role = $data['role'];

$hashed = password_hash($password, PASSWORD_BCRYPT);

// Check if user exists
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->rowCount() > 0) {
  echo json_encode(['message' => 'User already exists']);
  exit();
}

// Insert new user
$stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)");
if ($stmt->execute([$name, $email, $hashed, $role])) {
  echo json_encode(['message' => 'Registration successful']);
} else {
  echo json_encode(['message' => 'Something went wrong']);
}
?>
