<?php
require 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function verifyToken($token) {
  try {
    $decoded = JWT::decode($token, new Key('your_secret_key', 'HS256'));
    return (array)$decoded;
  } catch (Exception $e) {
    http_response_code(401);
    echo json_encode(['message' => 'Unauthorized']);
    exit();
  }
}
?>
