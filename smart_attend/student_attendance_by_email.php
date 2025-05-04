<?php
// student_attendance_by_email.php

// 1) CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Authorization, Content-Type");

// 2) Preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Preflight request, just return 200 and exit
    http_response_code(200);
    exit();
}

// 3) Boot and auth
require 'db.php';
require 'verify_token.php';

// Extract Bearer token
$headers = apache_request_headers();
$token = str_replace('Bearer ', '', $headers['Authorization'] ?? '');
$user = verifyToken($token);  // will send 401 if invalid

// 4) Validate input
$email = filter_input(INPUT_GET, 'email', FILTER_VALIDATE_EMAIL);
if (!$email) {
    http_response_code(400);
    echo json_encode(['message' => 'Missing or invalid email']);
    exit();
}

// 5) Query attendance by email
//    – joins through users→attendance→sessions→classes
$stmt = $pdo->prepare("
    SELECT
      c.name    AS class_name,
      s.session_date,
      a.status
    FROM attendance a
    JOIN users u      ON a.student_id = u.id
    JOIN sessions s   ON a.session_id = s.id
    JOIN classes c    ON s.class_id = c.id
    WHERE u.email = ?
    ORDER BY s.session_date DESC
");
$stmt->execute([$email]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 6) Return JSON
header('Content-Type: application/json');
echo json_encode($rows);
