<?php
// CORS headers to allow requests from your React dev server
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit(); }

require __DIR__ . '/db.php';
header('Content-Type: application/json');

// Parse request body
$data = json_decode(file_get_contents('php://input'), true);
$name     = trim($data['name'] ?? '');
$email    = trim($data['email'] ?? '');
$password = $data['password'] ?? '';

// Check for duplicate email
$stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetchColumn() > 0) {
  http_response_code(409);
  echo json_encode(['message'=>'User already exists']);
  exit();
}

// Hash password and insert new user (default role student)
$hash = password_hash($password, PASSWORD_BCRYPT);
$insert = $pdo->prepare("
  INSERT INTO users (name, email, password_hash, role)
  VALUES (?, ?, ?, ?)
");
if ($insert->execute([$name, $email, $hash])) {
  echo json_encode(['message'=>'Registration successful']);
} else {
  http_response_code(500);
  echo json_encode(['message'=>'Registration failed']);
}
