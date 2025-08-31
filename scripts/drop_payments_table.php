<?php

require __DIR__ . '/../vendor/autoload.php';

// Load environment
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$host = $_ENV['DB_HOST'] ?? '127.0.0.1';
$port = $_ENV['DB_PORT'] ?? '3306';
$db = $_ENV['DB_DATABASE'] ?? 'schooly';
$user = $_ENV['DB_USERNAME'] ?? 'root';
$pass = $_ENV['DB_PASSWORD'] ?? '';

$dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo "Connected to DB \n";
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
    $pdo->exec('DROP TABLE IF EXISTS `payments`');
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
    echo "Dropped payments table if existed\n";
} catch (PDOException $e) {
    echo "DB error: " . $e->getMessage() . "\n";
    exit(1);
}

