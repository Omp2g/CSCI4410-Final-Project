<?php
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Authorization, Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit(); }

require __DIR__ . '/../db.php';
require __DIR__ . '/verify_token.php';

verifyToken();

$classId = (int)($_GET['classId'] ?? 0);
$from    = $_GET['from'] ?? '1970-01-01';
$to      = $_GET['to']   ?? date('Y-m-d');

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="class_report.csv"');

$stmt = $pdo->prepare("
  SELECT u.name AS student, s.session_date AS date, a.status
  FROM attendance a
  JOIN sessions s ON s.id = a.session_id
  JOIN users u    ON u.id = a.student_id
  WHERE s.class_id = :classId
    AND s.session_date BETWEEN :from AND :to
  ORDER BY s.session_date, u.name
");
$stmt->execute([':classId'=>$classId, ':from'=>$from, ':to'=>$to]);

echo "Student,Date,Status\n";
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "{$row['student']},{$row['date']},{$row['status']}\n";
}
