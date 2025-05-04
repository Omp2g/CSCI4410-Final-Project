<?php
// smart_attend/add_enrollment.php

// ── CORS & preflight ─────────────────────────────────────────
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Authorization, Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Just return 200 for preflight
    exit(0);
}

header('Content-Type: application/json');

// ── Bootstrap & auth ──────────────────────────────────────────
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/verify_token.php';
require __DIR__ . '/db.php';

$hdrs  = apache_request_headers();
$token = str_replace('Bearer ', '', $hdrs['Authorization'] ?? '');
$user  = verifyToken($token);  
if ($user['role'] !== 'teacher') {
  http_response_code(403);
  exit(json_encode(['message'=>'Forbidden']));
}

// ── Parse input ────────────────────────────────────────────────
$input   = json_decode(file_get_contents('php://input'), true);
$classId = (int)($input['classId'] ?? 0);
$email   = trim($input['email'] ?? '');

if (!$classId || !$email) {
  http_response_code(400);
  exit(json_encode(['message'=>'classId and email required']));
}

// ── Lookup student by email ────────────────────────────────────
$stmt = $pdo->prepare("SELECT id,name FROM users WHERE email=? AND role='student'");
$stmt->execute([$email]);
$stu = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$stu) {
  http_response_code(404);
  exit(json_encode(['message'=>'Student not found']));
}

// ── Insert enrollment ──────────────────────────────────────────
try {
  $insert = $pdo->prepare("
    INSERT INTO enrollments (class_id, student_id)
    VALUES (?, ?)
  ");
  $insert->execute([$classId, $stu['id']]);
} catch (PDOException $e) {
  // e.g. duplicate enrollment
  http_response_code(400);
  exit(json_encode(['message'=>'Could not enroll student']));
}

// ── Return JSON so front-end’s res.json() succeeds ─────────────
echo json_encode([
  'message' => 'Student enrolled',
  'student' => $stu
]);
