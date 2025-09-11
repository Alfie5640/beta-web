<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/auth.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();
$secret = $_ENV['JWT_SECRET'];


header('Content-Type: application/json'); // Always return JSON

// Read raw POST data
$data = json_decode(file_get_contents('php://input'), true);
$response = ['success' => false, 'id'=>'', 'message' => '', 'username' => '', 'role' => ''];

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