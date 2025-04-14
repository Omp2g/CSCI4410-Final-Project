<?php
require 'db.php';
require 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// ✅ CORS headers for frontend-backend communication
header("Access-Control-Allow-Origin: *"); // Allow all origins — for dev only
header("Access-Control-Allow-Headers: Authorization, Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header('Content-Type: application/json');

// ✅ Handle preflight (OPTIONS) request sent by browsers
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit();
}

// ✅ Read JSON input
$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

// ✅ Query database
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

// ✅ Verify credentials
if ($user && password_verify($password, $user['password_hash'])) {
  $payload = [
    'user_id' => $user['user_id'],
    'role' => $user['role'],
    'exp' => time() + 3600 // Token expires in 1 hour
  ];

  $secret = 'your_secret_key'; // 🔐 Replace with a secure, long random string
  $jwt = JWT::encode($payload, $secret, 'HS256');

  echo json_encode([
    'token' => $jwt,
    'role' => $user['role'],
    'name' => $user['name']
  ]);
} else {
  http_response_code(401);
  echo json_encode(['message' => 'Invalid credentials']);
}
?>
