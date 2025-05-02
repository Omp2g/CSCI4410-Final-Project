<?php
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Authorization, Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit(); }

require __DIR__ . '/db.php';
require __DIR__ . '/verify_token.php';
header('Content-Type: application/json');

verifyToken();  // any logged-in user

$sid = (int)($_GET['sessionId'] ?? 0);
$cidStmt = $pdo->prepare("SELECT class_id FROM sessions WHERE id = ?");
$cidStmt->execute([$sid]);
$classId = $cidStmt->fetchColumn();

$stmt = $pdo->prepare("
  SELECT u.id, u.name
  FROM users u
  JOIN enrollments e ON e.student_id = u.id
  WHERE e.class_id = ?
");
$stmt->execute([$classId]);
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
