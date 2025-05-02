<?php
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Authorization, Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit(); }

require __DIR__ . '/../db.php';
require __DIR__ . '/verify_token.php';
header('Content-Type: application/json');

$token = verifyToken();
if ($token['role'] !== 'teacher') {
  http_response_code(403);
  exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$classId = $data['classId'] ?? 0;
$date    = $data['date']    ?? null;

$stmt = $pdo->prepare("INSERT INTO sessions (class_id, session_date) VALUES (?, ?)");
$stmt->execute([$classId, $date]);
echo json_encode(['id'=>$pdo->lastInsertId()]);
