<?php
// Change these values only if your XAMPP MySQL settings are different.
$host = '127.0.0.1';
$db = 'test';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$pdo = new PDO(
    "mysql:host=$host;dbname=$db;charset=$charset",
    $user,
    $pass,
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
);
