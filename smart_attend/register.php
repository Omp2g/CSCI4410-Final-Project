<?php
// register.php
require 'db.php';
require 'vendor/autoload.php';
use Firebase\JWT\JWT;

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Authorization, Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit;

$input = json_decode(file_get_contents('php://input'), true);
[$name,$email,$password,$role] = [
  $input['name'] ?? '',
  $input['email'] ?? '',
  $input['password'] ?? '',
  $input['role'] ?? 'student'
];

if (!$name || !$email || !$password) {
  http_response_code(400);
  echo json_encode(['message'=>'All fields are required']);
  exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);
try {
  $stmt = $pdo->prepare(
    "INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)"
  );
  $stmt->execute([$name,$email,$hash,$role]);
  $userId = $pdo->lastInsertId();
  
  $payload = [
    'user_id' => $userId,
    'role'    => $role,
    'name'    => $name,
    'exp'     => time() + 3600
  ];
  $jwt = JWT::encode($payload, 'your_secret_key', 'HS256');
  echo json_encode([
    'message' => 'Registration successful',
    'token' => $jwt,
    'role'  => $role,
    'name'  => $name
  ]);
} catch (PDOException $e) {
  http_response_code(400);
  echo json_encode(['message'=>'Email already in use']);
}
