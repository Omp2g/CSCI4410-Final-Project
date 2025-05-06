<?php
// smart_attend/remove_enrollment.php

// ── CORS & preflight ─────────────────────────────────────────
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Authorization, Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Return 200 OK on preflight
    exit(0);
}

header('Content-Type: application/json');

// ── Bootstrap & auth ──────────────────────────────────────────
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/verify_token.php';
require __DIR__ . '/db.php';

// Grab and verify JWT
$hdrs  = apache_request_headers();
$token = str_replace('Bearer ', '', $hdrs['Authorization'] ?? '');
$user  = verifyToken($token);

// Only teachers can remove students
if ($user['role'] !== 'teacher') {
    http_response_code(403);
    exit(json_encode(['message' => 'Forbidden']));
}

// ── Parse input ────────────────────────────────────────────────
$input     = json_decode(file_get_contents('php://input'), true);
$classId   = (int)($input['classId'] ?? 0);
$studentId = (int)($input['studentId'] ?? 0);

if (!$classId || !$studentId) {
    http_response_code(400);
    exit(json_encode(['message' => 'classId and studentId required']));
}

// ── Perform deletion ──────────────────────────────────────────
try {
    $stmt = $pdo->prepare("
        DELETE FROM enrollments
         WHERE class_id   = ?
           AND student_id = ?
    ");
    $stmt->execute([$classId, $studentId]);
    if ($stmt->rowCount() === 0) {
        // nothing deleted → perhaps wrong IDs
        http_response_code(404);
        exit(json_encode(['message' => 'Enrollment not found']));
    }
} catch (Exception $e) {
    http_response_code(500);
    exit(json_encode(['message' => 'Database error']));
}

// ── Success ───────────────────────────────────────────────────
echo json_encode([
    'message'   => 'Student removed',
    'classId'   => $classId,
    'studentId' => $studentId
]);
