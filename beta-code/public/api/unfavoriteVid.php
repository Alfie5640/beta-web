<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/auth.php';
include('../dbConnect.php');

function unfavorite($link, &$response,  $userId, $videoId) {
    
    $stmt = mysqli_prepare($link, "DELETE FROM Favourites WHERE userId = ? AND videoId = ?");
        
    if ($stmt === false) {
            http_response_code(500);
            $response['message'] = 'DB error ';
            return;
        }
        
    mysqli_stmt_bind_param($stmt, "ii", $userId, $videoId);
    
     if (!mysqli_stmt_execute($stmt)) {
            http_response_code(500); // Internal Server Error
            $response['message'] = "DB Error";
        } else {
            http_response_code(201); // Created
            $response['success'] = true;
            $response['message'] = "User registered successfully";
            
        }
        
        mysqli_stmt_close($stmt);
}


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();
$secret = $_ENV['JWT_SECRET'];

header('Content-Type: application/json'); // Always return JSON
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

$response = ['success' => false, 'message' => ''];

$headers = getallheaders();
$token = null;

if (isset($headers['Authorization']) && preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
    $token = $matches[1];
}

if (!$token) {
    http_response_code(401);
    $response['message'] = 'No token provided';
    echo json_encode($response);
    exit;
}

//Verifies the JWT
$payload = verifyJWT($token, $secret);


if (!$payload) {
    $response['message'] = 'Invalid or expired token';
    echo json_encode($response);
    exit;
}

$userId = $payload['id'];


$data = json_decode(file_get_contents('php://input'), true);
$videoId = $data['videoId'];


unfavorite($link, $response, $userId, $videoId);

echo(json_encode($response));
?>