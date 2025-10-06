<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/auth.php';
include('../dbConnect.php');

function sanitiseInputs(&$response, $labelText) {
    if($labelText != filter_var($labelText, @FILTER_SANITIZE_STRING)) {
        http_response_code(400); // Bad Request
        $response['message'] = "Non-conforming characters in the custom label field. Please review and re-enter this field";
        $response['success'] = false;
        echo(json_encode($response));
        exit;
    }
        
}


function submitLabel($link, &$response, $vidId, $userId, $xPos, $yPos, $timestamp, $labelText) {
    
    $stmt = mysqli_prepare($link, "INSERT INTO Labels (userId, videoId, label_text, xPosition, yPosition, label_time) VALUES (?,?,?,?,?,?)");
    
    if($stmt) {
        
        mysqli_stmt_bind_param($stmt, 'iisddd', $userId, $vidId, $labelText, $xPos, $yPos, $timestamp);
        
        if (mysqli_stmt_execute($stmt)) {
                $response['success'] = true;
                $response['message'] = "Label Added";
                
            } else {
                http_response_code(500);
                $response['message'] = "Database insert failed";
            }
        
    } else {
        http_response_code(500);
        $response['message'] = "DB Error";
    }
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
    $response['message'] = 'No token provided';
    echo json_encode($response);
    exit;
}

    
$data = json_decode(file_get_contents('php://input'), true);

//Verifies the JWT
$payload = verifyJWT($token, $secret);


if ($payload) {
    $response['success'] = true;
} else {
    $response['message'] = 'Invalid or expired token';
    echo json_encode($response);
    exit;
}


$vidId = intval($_GET['id']);
$userId = $payload['id'];
$xPos = $data['xPos'];
$yPos = $data['yPos'];
$timestamp = $data['timestamp'];
$labelText = $data['labelType'];

sanitiseInputs($response, $labelText);
submitLabel($link, $response, $vidId, $userId, $xPos, $yPos, $timestamp, $labelText);

echo(json_encode($response));
?>
