<?php
// smart_attend/add_session.php

// ── CORS & Preflight ───────────────────────────────────────
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Authorization, Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

header('Content-Type: application/json');

// ── Bootstrap & Auth ───────────────────────────────────────
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/verify_token.php';
require __DIR__ . '/db.php';

$hdrs  = apache_request_headers();
$token = str_replace('Bearer ', '', $hdrs['Authorization'] ?? '');
$user  = verifyToken($token);

if ($user['role'] !== 'teacher') {
    http_response_code(403);
    exit(json_encode(['message' => 'Forbidden']));
}

// ── Parse & Validate Input ──────────────────────────────────
$body      = json_decode(file_get_contents('php://input'), true);
$classId   = isset($body['classId'])      ? (int)$body['classId']      : 0;
$sessionDt = isset($body['sessionDate'])  ? trim($body['sessionDate']) : '';

if (!$classId || !$sessionDt) {
    http_response_code(400);
    exit(json_encode(['message' => 'classId and sessionDate required']));
}

// Validate date format (YYYY-MM-DD)
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $sessionDt)) {
    http_response_code(400);
    exit(json_encode(['message' => 'Invalid date format']));
}

// ── Insert New Session ─────────────────────────────────────
try {
    $stmt = $pdo->prepare("
      INSERT INTO sessions (class_id, session_date)
      VALUES (?, ?)
    ");
    $stmt->execute([$classId, $sessionDt]);
    $newId = $pdo->lastInsertId();

    echo json_encode([
      'message'      => 'Session created',
      'sessionId'    => $newId,
      'classId'      => $classId,
      'sessionDate'  => $sessionDt
    ]);
} catch (PDOException $e) {
    // Duplicate key (session already exists)
    if ($e->getCode() === '23000') {
        http_response_code(400);
        exit(json_encode(['message' => 'Session for that date already exists']));
    }
    // Other DB error
    http_response_code(500);
    exit(json_encode(['message' => 'Database error']));
}
