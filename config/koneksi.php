<?php
// config/koneksi.php

$host = 'localhost';
$db   = 'db_splj';
$user = 'root';
$pass = 'parri100';
$charset = 'utf8mb4';

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => true, // Enable multiple statements execution for seeding
];

try {
     // Connect to MySQL server first without specifying db to avoid "Unknown database" errors
     $dsnWithoutDb = "mysql:host=$host;charset=$charset";
     $pdo = new PDO($dsnWithoutDb, $user, $pass, $options);
     
     // Create database if not exists
     $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
     $pdo->exec("USE `$db`;");
     
     // Check if tables exist by querying schema, if not seed database
     $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
     if ($stmt->rowCount() === 0) {
         $sqlFile = dirname(__DIR__) . '/database/db_splj.sql';
         if (file_exists($sqlFile)) {
             $sqlContent = file_get_contents($sqlFile);
             $pdo->exec($sqlContent);
         }
     }
     
     $conn = $pdo;
     
     // Legacy connection support
     $koneksi = mysqli_connect($host, $user, $pass, $db);
     
} catch (\PDOException $e) {
     // Render premium informative error block instead of throwing 500 error
     http_response_code(500);
     echo "
     <div style='max-width: 600px; margin: 50px auto; padding: 30px; background: #fff; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); font-family: system-ui, sans-serif; color: #1e293b; border-left: 6px solid #ef4444;'>
         <h2 style='color: #dc2626; margin-top: 0; display: flex; align-items: center;'>
             <span style='margin-right: 10px;'>⚠️</span> Koneksi Database Gagal
         </h2>
         <p style='line-height: 1.6; color: #475569;'>Gagal terhubung ke MySQL. Silakan periksa hal-hal berikut:</p>
         <ul style='line-height: 1.6; color: #475569;'>
             <li>Apakah service MySQL/MariaDB sudah diaktifkan?</li>
             <li>Apakah username dan password di <code>config/koneksi.php</code> sudah sesuai?</li>
         </ul>
         <div style='background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0; font-family: monospace; font-size: 0.9rem; margin-top: 20px; overflow-x: auto;'>
             <strong>Pesan Error:</strong><br>" . htmlspecialchars($e->getMessage()) . "
         </div>
     </div>";
     exit;
}