<?php
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit(); }

require __DIR__ . '/db.php';
require __DIR__ . '/../vendor/autoload.php';
use Firebase\JWT\JWT;

header('Content-Type: application/json');

// Parse JSON
$data = json_decode(file_get_contents('php://input'), true);
$email    = trim($data['email'] ?? '');
$password = $data['password'] ?? '';

// Look up user
$stmt = $pdo->prepare("SELECT id, name, password_hash, role FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password_hash'])) {
  $payload = [
    'sub' => $user['id'],
    'name'=> $user['name'],
    'role'=> $user['role'],
    'iat' => time(),
    'exp' => time() + 3600
  ];
  $jwt = JWT::encode($payload, 'your_secret_key', 'HS256');
  echo json_encode(['token'=>$jwt, 'role'=>$user['role'], 'name'=>$user['name']]);
} else {
  http_response_code(401);
  echo json_encode(['message'=>'Invalid credentials']);
}
