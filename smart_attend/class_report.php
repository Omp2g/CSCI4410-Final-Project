<?php
// class_report.php
require 'db.php';
require 'verify_token.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
  http_response_code(405);
  exit;
}

$token   = $_GET['token']   ?? '';
$user    = verifyToken($token);
$classId = $_GET['classId'] ?? '';
$from    = $_GET['from']    ?? null;
$to      = $_GET['to']      ?? null;

if ($user['role'] !== 'teacher' || !$classId) {
  http_response_code(403);
  exit;
}

// Build query
$sql = "
  SELECT u.name, s.session_date AS date, a.status
  FROM attendance a
  JOIN sessions sess ON a.session_id = sess.id
  JOIN users u ON a.student_id = u.id
  WHERE sess.class_id = ?
";
$params = [$classId];

// Date filter
if ($from) {
  $sql .= " AND sess.session_date >= ?";
  $params[] = $from;
}
if ($to) {
  $sql .= " AND sess.session_date <= ?";
  $params[] = $to;
}
$sql .= " ORDER BY sess.session_date ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();

// Output CSV
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="class_report.csv"');
$out = fopen('php://output', 'w');
fputcsv($out, ['Student','Date','Status']);
foreach ($rows as $r) {
  fputcsv($out, [$r['name'], $r['date'], $r['status']]);
}
fclose($out);
