<?php
// smart_attend/get_attendance.php

// ── CORS & Preflight ───────────────────────────────────────
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
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

// ── Validate input ─────────────────────────────────────────
$sessionId = isset($_GET['sessionId']) ? (int)$_GET['sessionId'] : 0;
if (!$sessionId) {
  http_response_code(400);
  exit(json_encode(['message'=>'sessionId required']));
}

// ── Fetch attendance records ───────────────────────────────
$stmt = $pdo->prepare("
  SELECT student_id, status
    FROM attendance
   WHERE session_id = ?
");
$stmt->execute([$sessionId]);
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ── Return JSON ─────────────────────────────────────────────
echo json_encode($records);
