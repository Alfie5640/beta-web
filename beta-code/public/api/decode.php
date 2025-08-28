<?php
require_once __DIR__ . '/../vendor/autoload.php';



// Helper: decode base64url
function base64UrlDecode($data) {
    $remainder = strlen($data) % 4;
    if ($remainder) $data .= str_repeat('=', 4 - $remainder);
    return base64_decode(strtr($data, '-_', '+/'));
}

// Verify JWT
function verifyJWT($jwt, $secret) {
    $parts = explode('.', $jwt);
    if (count($parts) !== 3) return false;

    list($header64, $payload64, $signature64) = $parts;

    $signature = hash_hmac('sha256', "$header64.$payload64", $secret, true);
    $expectedSig = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

    if (!hash_equals($expectedSig, $signature64)) return false;

    $payload = json_decode(base64UrlDecode($payload64), true);
    if (!$payload || !isset($payload['exp']) || $payload['exp'] < time()) return false;

    return $payload;
}



$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();
$secret = $_ENV['JWT_SECRET'];


header('Content-Type: application/json'); // Always return JSON

// Read raw POST data
$data = json_decode(file_get_contents('php://input'), true);
$response = ['success' => false, 'message' => '', 'username' => '', 'role' => ''];

// Check if token is provided
if (!isset($data['token'])) {
    $response['message'] = 'No token provided';
    echo json_encode($response);
    exit;
}

$token = $data['token'];

$payload = verifyJWT($token, $secret);


if ($payload) {
    $response['success'] = true;
    $response['username'] = $payload['username'];
    $response['role'] = $payload['role'];
} else {
    $response['message'] = 'Invalid or expired token';
}

echo json_encode($response);

?>