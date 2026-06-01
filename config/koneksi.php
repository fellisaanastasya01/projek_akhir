<?php
// config/koneksi.php

$host = 'localhost';
$db   = 'db_splj';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// For compatibility with old scripts that might use $conn or $koneksi as mysqli
// We will also instantiate a mysqli connection
$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    // If mysqli fails but PDO succeeded, we still proceed but note it.
    // Or we can just let it fail silently or log it.
}
$conn = $pdo; // $conn is the PDO instance