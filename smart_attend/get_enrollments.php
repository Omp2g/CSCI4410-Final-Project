<?php
// smart_attend/get_enrollments.php

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
$classId = isset($_GET['classId']) ? (int)$_GET['classId'] : 0;
if (!$classId) {
  http_response_code(400);
  exit(json_encode(['message'=>'classId required']));
}

// ── Fetch enrolled students ─────────────────────────────────
$stmt = $pdo->prepare("
  SELECT u.id, u.name
    FROM enrollments e
    JOIN users       u ON e.student_id = u.id
   WHERE e.class_id = ?
");
$stmt->execute([$classId]);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ── Return JSON ─────────────────────────────────────────────
echo json_encode($students);


