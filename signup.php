<?php
// --- HEADERS ---
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// --- DATABASE CONNECTION ---
require 'db.php'; // $db = PDO instance

// --- ENSURE POST REQUEST ---
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// --- REQUIRED FIELDS ---
$requiredFields = ['brandname', 'email', 'location', 'call_number', 'whatsapp_number'];
foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
        echo json_encode(['success' => false, 'message' => "Missing required field: $field"]);
        exit;
    }
}

// --- SANITIZE INPUTS ---
$brandname       = trim($_POST['brandname']);
$email           = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
$location        = trim($_POST['location']);
$address         = isset($_POST['address']) ? trim($_POST['address']) : null;
$call_number     = trim($_POST['call_number']);
$whatsapp_number = trim($_POST['whatsapp_number']);

// --- CHECK IF EMAIL EXISTS ---
$stmt = $db->prepare("SELECT id FROM sellers WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'Email already registered']);
    exit;
}

// --- SLUGIFY FUNCTION ---
function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = trim($text, '-');
    $text = strtolower($text);
    return $text ?: 'store-' . uniqid();
}
$slug = slugify($brandname);

// --- USE DEFAULT LOGO ---
$logoPath = "https://microcart.pxxl.click/1000329330.png"; // your placeholder logo

// --- INSERT SELLER (AUTO-VERIFIED) ---
$stmt = $db->prepare("
    INSERT INTO sellers (brandname, email, call_number, whatsapp_number, address, verified, profile_image)
    VALUES (?, ?, ?, ?, ?, 1, ?)
");
$stmt->execute([$brandname, $email, $call_number, $whatsapp_number, $address, $logoPath]);
$userId = $db->lastInsertId();

// --- INSERT STOREFRONT ---
$stmt = $db->prepare("
    INSERT INTO storefronts (name, logo, slug, location, user_id)
    VALUES (?, ?, ?, ?, ?)
");
$stmt->execute([$brandname, $logoPath, $slug, $location, $userId]);
$storeId = $db->lastInsertId();

// --- OPTIONAL EMAIL VERIFICATION (comment if not needed) ---
/*
$verifyToken = bin2hex(random_bytes(32));
$expiresAt = date('Y-m-d H:i:s', strtotime('+1 day'));

$stmt = $db->prepare("
    INSERT INTO email_verifications (user_id, token, expires_at)
    VALUES (?, ?, ?)
");
$stmt->execute([$userId, $verifyToken, $expiresAt]);

$verifyLink = "https://microcart.pxxl.click/verify.php?token=$verifyToken";
$subject = "Verify your Microcart account";
$message = "Hi $brandname,\n\nPlease verify your account:\n$verifyLink\n\nExpires in 24 hours.";
$headers = "From: no-reply@microcart.com\r\n";
@mail($email, $subject, $message, $headers);
*/

// --- API TOKEN ---
$apiToken = bin2hex(random_bytes(32));
$stmt = $db->prepare("UPDATE sellers SET api_token = ? WHERE id = ?");
$stmt->execute([$apiToken, $userId]);

// --- RESPONSE ---
echo json_encode([
    'success' => true,
    'message' => 'Signup successful! Account is verified.',
    'userId'  => $userId,
    'storeId' => $storeId,
    'token'   => $apiToken,
    'logo'    => $logoPath
]);
?>
