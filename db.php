<?php
// ===========================================
// DATABASE CONNECTION (PDO)
// ===========================================

// Your actual database credentials:
$host = "db.pxxl.pro";
$port = "20424";
$dbname = "db_abadad14";
$user = "user_74fb784b";
$pass = "9c5c428a9495a5ece0ae75f6b748b2f9";

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
