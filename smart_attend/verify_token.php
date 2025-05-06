<?php
// verify_token.php
require __DIR__ . '/vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function verifyToken(string $token) {
  try {
    $secret = 'your_secret_key';
    $decoded = JWT::decode($token, new Key($secret, 'HS256'));
    return (array)$decoded;
  } catch (Exception $e) {
    http_response_code(401);
    echo json_encode(['message'=>'Invalid or expired token']);
    exit;
  }
}

