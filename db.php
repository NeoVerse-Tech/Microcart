<?php
// ===========================================
// DATABASE CONNECTION (PDO)
// ===========================================

// Replace these manually
$host = "YOUR_DB_HOST";
$port = "YOUR_DB_PORT"; 
$dbname = "YOUR_DB_NAME";
$user = "YOUR_DB_USER";
$pass = "YOUR_DB_PASSWORD";

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
