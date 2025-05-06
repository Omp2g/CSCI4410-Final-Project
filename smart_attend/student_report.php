<?php
// smart_attend/student_report.php
require __DIR__.'/vendor/autoload.php';
require __DIR__.'/verify_token.php';
require __DIR__.'/db.php';

// no JSON here, we’ll send CSV
header('Access-Control-Allow-Origin: *');

$token = str_replace('Bearer ','', $_GET['token'] ?? '');
$user  = verifyToken($token);
if($user['role']!=='teacher'){
  http_response_code(403);
  exit('Forbidden');
}

$email = $_GET['email'] ?? '';
$from  = $_GET['from']  ?? null;
$to    = $_GET['to']    ?? null;
if(!$email){
  http_response_code(400);
  exit('Email required');
}

// lookup student
$stmt = $pdo->prepare("SELECT id,name FROM users WHERE email=? AND role='student'");
$stmt->execute([$email]);
$stu = $stmt->fetch();
if(!$stu){
  http_response_code(404);
  exit('Student not found');
}

// build date filter
$filter = "";
$params = [$stu['id']];
if($from){
  $filter .= " AND s.session_date >= ?";
  $params[] = $from;
}
if($to){
  $filter .= " AND s.session_date <= ?";
  $params[] = $to;
}

// fetch attendance rows
$query = "
  SELECT c.name, s.session_date, a.status
    FROM attendance a
    JOIN sessions s ON a.session_id = s.id
    JOIN classes  c ON s.class_id   = c.id
   WHERE a.student_id = ?
   $filter
   ORDER BY s.session_date
";
$stmt = $pdo->prepare($query);
$stmt->execute($params);

// stream CSV
header('Content-Type: text/csv');
header('Content-disposition: attachment; filename=report_'.$stu['name'].'.csv');
$out = fopen('php://output','w');
fputcsv($out, ['Course','Date','Status']);
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
  fputcsv($out, [$row['name'], $row['session_date'], $row['status']]);
}
fclose($out);
exit;
