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

$stmt = $pdo->prepare("
  SELECT s.id, c.name AS course_name, s.session_date
  FROM sessions s
  JOIN classes c ON s.class_id = c.id
  WHERE c.teacher_id = ?
  ORDER BY s.session_date DESC
");
$stmt->execute([$token['sub']]);
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
