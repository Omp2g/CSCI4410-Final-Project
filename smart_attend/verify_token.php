<?php
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Authorization, Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit(); }

require __DIR__ . 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function verifyToken() {
  $headers = getallheaders();
  if (empty($headers['Authorization'])) {
    http_response_code(401);
    echo json_encode(['message'=>'Token not provided']);
    exit();
  }
  list(, $token) = explode(' ', $headers['Authorization'], 2);
  try {
    $decoded = JWT::decode($token, new Key('your_secret_key','HS256'));
    return (array)$decoded;
  } catch (Exception $e) {
    http_response_code(401);
    echo json_encode(['message'=>'Unauthorized','error'=>$e->getMessage()]);
    exit();
  }
}
