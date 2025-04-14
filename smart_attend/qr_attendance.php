<?php
require 'db.php';
require 'verify_token.php';

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Authorization, Content-Type");

$headers = apache_request_headers();
$token = str_replace('Bearer ', '', $headers['Authorization']);
$user = verifyToken($token);

$data = json_decode(file_get_contents('php://input'), true);

$studentStmt = $pdo->prepare("SELECT student_id FROM students WHERE user_id = ?");
$studentStmt->execute([$user['user_id']]);
$student_id = $studentStmt->fetchColumn();

if (!$student_id) {
  http_response_code(403);
  echo json_encode(['message' => 'Student not registered for any class']);
  exit();
}

$checkStmt = $pdo->prepare("SELECT * FROM attendance WHERE student_id = ? AND class_id = ? AND date = ?");
$checkStmt->execute([$student_id, $data['class_id'], $data['date']]);

if ($checkStmt->rowCount() === 0) {
  $insertStmt = $pdo->prepare("INSERT INTO attendance (student_id, class_id, date, status) VALUES (?, ?, ?, 'present')");
  $insertStmt->execute([$student_id, $data['class_id'], $data['date']]);
  echo json_encode(['message' => 'Attendance marked successfully']);
} else {
  echo json_encode(['message' => 'You have already marked attendance for today.']);
}
?>
