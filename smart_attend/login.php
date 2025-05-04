<?php
// login.php
require 'db.php';
require 'vendor/autoload.php';
use Firebase\JWT\JWT;

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Authorization, Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit;

$input = json_decode(file_get_contents('php://input'), true);
$email    = $input['email']    ?? '';
$password = $input['password'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password_hash'])) {
  $payload = [
    'user_id' => $user['id'],
    'role'    => $user['role'],
    'name'    => $user['name'],
    'exp'     => time() + 3600
  ];
  $jwt = JWT::encode($payload, 'your_secret_key', 'HS256');
  echo json_encode([
    'token' => $jwt,
    'role'  => $user['role'],
    'name'  => $user['name']
  ]);
} else {
  http_response_code(401);
  echo json_encode(['message'=>'Invalid credentials']);
}


