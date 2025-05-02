<?php
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Authorization, Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit(); }

require __DIR__ . '/db.php';
require __DIR__ . '/verify_token.php';
header('Content-Type: application/json');

verifyToken();

$sid = (int)($_GET['sessionId'] ?? 0);
$stmt = $pdo->prepare("
  SELECT student_id, status
  FROM attendance
  WHERE session_id = ?
");
$stmt->execute([$sid]);
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
