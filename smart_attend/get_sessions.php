<?php
// get_sessions.php
require 'db.php';
require 'verify_token.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Authorization, Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit;

$token   = str_replace('Bearer ', '', getallheaders()['Authorization'] ?? '');
$user    = verifyToken($token);
if ($user['role'] !== 'teacher') {
  http_response_code(403);
  echo json_encode(['message'=>'Forbidden']);
  exit;
}

$stmt = $pdo->prepare(
  "SELECT s.id, c.name AS course_name, s.session_date, s.class_id
   FROM sessions s
   JOIN classes c ON s.class_id = c.id
   WHERE c.teacher_id = ?
   ORDER BY s.session_date DESC"
);
$stmt->execute([$user['user_id']]);
echo json_encode($stmt->fetchAll());
