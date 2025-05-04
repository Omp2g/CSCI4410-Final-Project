<?php
// smart_attend/get_session_detail.php

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

// ── Fetch session + class info ─────────────────────────────
$stmt = $pdo->prepare("
  SELECT
    s.id,
    s.class_id,
    s.session_date,
    c.name AS class_name
  FROM sessions s
  JOIN classes c ON s.class_id = c.id
  WHERE s.id = ?
");
$stmt->execute([$sessionId]);
$session = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$session) {
  http_response_code(404);
  exit(json_encode(['message'=>'Session not found']));
}

// ── Return JSON ─────────────────────────────────────────────
echo json_encode($session);
