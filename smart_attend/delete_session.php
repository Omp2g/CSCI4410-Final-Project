<?php
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Authorization, Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit(); }

require __DIR__ . '/db.php';
require __DIR__ . '/verify_token.php';
header('Content-Type: application/json');

$token = verifyToken();
if ($token['role'] !== 'teacher') {
  http_response_code(403);
  exit();
}

$sid = (int)($_GET['sessionId'] ?? 0);
$pdo->prepare("DELETE FROM attendance WHERE session_id = ?")->execute([$sid]);
$pdo->prepare("DELETE FROM sessions WHERE id = ?")->execute([$sid]);
echo json_encode(['deleted'=>true]);
