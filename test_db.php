<?php
require 'db.php'; // your PDO connection file

header('Content-Type: text/plain'); // plain text output for easy reading

try {
    // 1️⃣ Insert a test seller
    $stmt = $pdo->prepare("
        INSERT INTO sellers (brandname, email, api_token, verified, created_at)
        VALUES (?, ?, ?, 1, NOW())
    ");
    $brandname = "Test Seller";
    $email     = "test_seller@example.com";
    $api_token = bin2hex(random_bytes(16));
    $stmt->execute([$brandname, $email, $api_token]);

    $sellerId = $pdo->lastInsertId();

    // 2️⃣ Fetch it back
    $stmt = $pdo->prepare("SELECT id, brandname, email, api_token FROM sellers WHERE id = ?");
    $stmt->execute([$sellerId]);
    $seller = $stmt->fetch();

    echo "✅ DB Test Successful!\n";
    echo "Inserted Seller ID: " . $seller['id'] . "\n";
    echo "Brand Name: " . $seller['brandname'] . "\n";
    echo "Email: " . $seller['email'] . "\n";
    echo "API Token: " . $seller['api_token'] . "\n";

} catch (Exception $e) {
    echo "❌ DB Test Failed: " . $e->getMessage();
}
?>
