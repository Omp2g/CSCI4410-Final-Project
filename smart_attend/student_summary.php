<?php
// student_summary.php
require 'db.php';
require 'verify_token.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Authorization, Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit;

$token  = str_replace('Bearer ', '', getallheaders()['Authorization'] ?? '');
$user   = verifyToken($token);

// Calculate % per class
$stmt = $pdo->prepare("
  SELECT c.name AS course,
         ROUND(
           SUM(a.status='present') / COUNT(*) * 100
         ) AS percentage
  FROM attendance a
  JOIN sessions s   ON a.session_id = s.id
  JOIN classes c    ON s.class_id = c.id
  WHERE a.student_id = ?
  GROUP BY c.id
");
$stmt->execute([$user['user_id']]);
echo json_encode($stmt->fetchAll());
