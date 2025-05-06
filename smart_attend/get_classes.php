<?php
// get_classes.php
require 'db.php';
require 'verify_token.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Authorization, Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit;

$token = str_replace('Bearer ', '', getallheaders()['Authorization'] ?? '');
$user  = verifyToken($token);

if ($user['role'] !== 'teacher') {
  http_response_code(403);
  exit;
}

$stmt = $pdo->prepare("SELECT id, name FROM classes WHERE teacher_id = ?");
$stmt->execute([$user['user_id']]);
echo json_encode($stmt->fetchAll());
