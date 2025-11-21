<?php
// index.php - Microcart Entry Point

// --------------------------
// Database Configuration
// --------------------------
$DB_HOST = 'db.pxxl.pro';
$DB_PORT = 20424;
$DB_NAME = 'db_abadad14';
$DB_USER = 'user_74fb784b';
$DB_PASS = '9c5c428a9495a5ece0ae75f6b748b2f9';

// --------------------------
// Connect to Database
// --------------------------
$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}

// --------------------------
// Optional: Redirect to index.html if it exists
// --------------------------
if (file_exists('index.html')) {
    header("Location: index.html");
    exit;
}

// --------------------------
// Fallback message if no index.html
// --------------------------
echo "Microcart PHP environment is working! Database connection successful.";
?>
