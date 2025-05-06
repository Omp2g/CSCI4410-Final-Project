<?php
// attendance_recent.php
require 'db.php';
require 'verify_token.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Authorization, Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit;

$token = str_replace('Bearer ', '', getallheaders()['Authorization'] ?? '');
$user  = verifyToken($token);

$stmt = $pdo->prepare(
  "SELECT s.id, c.name AS course, s.session_date AS date, a.status
   FROM sessions s
   JOIN attendance a ON s.id = a.session_id
   JOIN classes c ON s.class_id = c.id
   WHERE a.student_id = ?
   ORDER BY s.session_date DESC
   LIMIT 5"
);
$stmt->execute([$user['user_id']]);
echo json_encode($stmt->fetchAll());
