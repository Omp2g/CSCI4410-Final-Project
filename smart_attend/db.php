<?php
$host = 'localhost';
$db = 'smart_attend';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
  'mysql:host=localhost;dbname=attendance_system;charset=utf8mb4', // DSN
        'db_user',    // MySQL username
        'db_password',// MySQL password
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION] // throw exceptions on errors
  
} catch (PDOException $e) {
   // If connection fails, output JSON error and stop
   header('Content-Type: application/json', true, 500);
   echo json_encode(['message' => 'Database connection failed', 'error' => $e->getMessage()]);
   exit();
}
?>
