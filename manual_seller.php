
<?php
require_once "db.php";   // connects to $pdo

header("Content-Type: application/json");

// Read raw POST body
$input = json_decode(file_get_contents("php://input"), true);

// Required fields
if (!isset($input["brandname"]) || !isset($input["email"])) {
    echo json_encode([
        "success" => false,
        "message" => "Missing required fields: brandname or email"
    ]);
    exit;
}

// Sanitize
$brandname = trim($input["brandname"]);
$email     = trim($input["email"]);
$call      = $input["call_number"] ?? null;
$whatsapp  = $input["whatsapp_number"] ?? null;
$address   = $input["address"] ?? null;
$profile   = $input["profile_image"] ?? "/default-avatar.png";

// Generate token
$api_token = bin2hex(random_bytes(16)); // 32-char token

try {
    $stmt = $pdo->prepare("
        INSERT INTO sellers 
        (brandname, email, call_number, whatsapp_number, address, profile_image, verified, api_token, created_at)
        VALUES 
        (:brandname, :email, :call, :whatsapp, :address, :profile, 1, :token, NOW())
    ");

    $stmt->execute([
        ":brandname" => $brandname,
        ":email"     => $email,
        ":call"      => $call,
        ":whatsapp"  => $whatsapp,
        ":address"   => $address,
        ":profile"   => $profile,
        ":token"     => $api_token
    ]);

    echo json_encode([
        "success" => true,
        "message" => "Seller created successfully",
        "sellerId" => $pdo->lastInsertId(),
        "api_token" => $api_token
    ]);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>
