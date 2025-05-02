<?php
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Authorization, Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit(); }

require __DIR__ . '/db.php';
require __DIR__ . '/verify_token.php';
header('Content-Type: application/json');

verifyToken();

$data = json_decode(file_get_contents('php://input'), true);
$upsert = $pdo->prepare("
  INSERT INTO attendance (session_id, student_id, status)
  VALUES (?, ?, ?)
  ON DUPLICATE KEY UPDATE status = ?
");

foreach ($data['records'] as $r) {
  $upsert->execute([
    $data['sessionId'],
    $r['student_id'],
    $r['status'],
    $r['status']
  ]);
}

echo json_encode(['success'=>true]);
