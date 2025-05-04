<?php
// attendance_summary.php
require 'db.php';
require 'verify_token.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Authorization, Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit;

$token = str_replace('Bearer ', '', getallheaders()['Authorization'] ?? '');
$user  = verifyToken($token);

$stmt = $pdo->prepare(
  "SELECT status, COUNT(*) AS cnt 
   FROM attendance a 
   JOIN enrollments e ON a.student_id = e.student_id
   WHERE e.student_id = ?
   GROUP BY status"
);
$stmt->execute([$user['user_id']]);
$rows = $stmt->fetchAll();

$summary = ['present'=>0,'absent'=>0];
foreach ($rows as $r) {
  $summary[$r['status']] = (int)$r['cnt'];
}
echo json_encode($summary);
