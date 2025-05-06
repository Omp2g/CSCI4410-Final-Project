<?php
// smart_attend/add_class.php

// Allow from any origin and support preflight
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Authorization, Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Just return 200 for preflight
    exit(0);
}

header('Content-Type: application/json');

// Bootstrap JWT & DB
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/verify_token.php';
require __DIR__ . '/db.php';

// Pull and verify the token
$hdrs  = apache_request_headers();
$token = str_replace('Bearer ', '', $hdrs['Authorization'] ?? '');
$user  = verifyToken($token);

// Only teachers may add classes
if ($user['role'] !== 'teacher') {
  http_response_code(403);
  echo json_encode(['message' => 'Forbidden']);
  exit();
}

// Parse JSON body
$input = json_decode(file_get_contents('php://input'), true);
$name  = trim($input['name'] ?? '');

if (!$name) {
  http_response_code(400);
  echo json_encode(['message' => 'Class name required']);
  exit();
}

// Insert into DB
try {
  $stmt = $pdo->prepare("INSERT INTO classes (name, teacher_id) VALUES (?, ?)");
  $stmt->execute([$name, $user['user_id']]);
  echo json_encode([
    'message' => 'Class added',
    'id'      => $pdo->lastInsertId()
  ]);
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['message' => 'Database error']);
}
