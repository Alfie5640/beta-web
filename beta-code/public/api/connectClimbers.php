<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/auth.php';
include('../dbConnect.php');

 function sanitiseInputs($response, $climberToAdd) {
    if($climberToAdd != filter_var($climberToAdd, @FILTER_SANITIZE_STRING)) {
        http_response_code(400); // Bad Request
        $response['message'] = "Non-conforming characters in the username field. Please review and re-enter this field";
        $response['success'] = false;
        echo(json_encode($response));
        exit;
    } 
}

function AddClimber($response, $climberToAdd, $climberId) {
    
}

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();
$secret = $_ENV['JWT_SECRET'];

header('Content-Type: application/json'); // Always return JSON
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: POST');
    
$data = json_decode(file_get_contents('php://input'), true);
$response = ['success' => false, 'message' => ''];

if (!isset($data['token'])) {
    $response['message'] = 'No token provided';
    echo json_encode($response);
    exit;
}

//Verifies the JWT

$token = $data['token'];
$payload = verifyJWT($token, $secret);


if ($payload) {
    $response['success'] = true;
} else {
    $response['message'] = 'Invalid or expired token';
    echo json_encode($response);
    exit;
}

//Verifies/sanitises inputs and inserts data in database
$climberToAdd = $data['climberName'];
$climberId = $payload['id'];

sanitiseInputs($response, $climberToAdd);
AddClimber($response, $climberToAdd, $climberId);

?>