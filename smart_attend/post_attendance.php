<?php
// smart_attend/post_attendance.php

// ── CORS & Preflight ───────────────────────────────────────
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Authorization, Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}
header('Content-Type: application/json');

// ── Bootstrap ─────────────────────────────────────────────
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/verify_token.php';
require __DIR__ . '/db.php';

// ── Auth ──────────────────────────────────────────────────
$hdrs  = apache_request_headers();
$token = str_replace('Bearer ', '', $hdrs['Authorization'] ?? '');
$user  = verifyToken($token);
if ($user['role'] !== 'teacher') {
  http_response_code(403);
  exit(json_encode(['message'=>'Forbidden']));
}

// ── Parse & Validate Input ──────────────────────────────────
$body = json_decode(file_get_contents('php://input'), true);
$sessionId = isset($body['sessionId']) ? (int)$body['sessionId'] : 0;
$records   = $body['records'] ?? [];

if (!$sessionId || !is_array($records)) {
  http_response_code(400);
  exit(json_encode(['message'=>'sessionId and records array required']));
}

// ── Upsert each attendance record ──────────────────────────
$stmt = $pdo->prepare("
  INSERT INTO attendance (session_id, student_id, status)
  VALUES (?, ?, ?)
  ON DUPLICATE KEY UPDATE status = VALUES(status)
");

try {
  foreach ($records as $r) {
    $sid    = (int)($r['student_id'] ?? 0);
    $status = in_array($r['status'], ['present','absent']) ? $r['status'] : 'absent';
    if ($sid) {
      $stmt->execute([$sessionId, $sid, $status]);
    }
  }
} catch (Exception $e) {
  http_response_code(500);
  exit(json_encode(['message'=>'Database error saving attendance']));
}

// ── Success ─────────────────────────────────────────────────
echo json_encode(['message'=>'Attendance saved']);
