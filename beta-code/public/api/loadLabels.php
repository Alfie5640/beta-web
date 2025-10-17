<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/auth.php';
include('../dbConnect.php');


function retrieveDetails($link, &$response, $vidId) {
    $stmt = mysqli_prepare($link, "SELECT label_text, xPosition, yPosition, label_time, labelId FROM Labels WHERE videoId = ?");
    
    if($stmt === false) {
        http_response_code(500);
        $response['message'] = "DB Error";
        echo json_encode($response);
        exit;
    }
    
    mysqli_stmt_bind_param($stmt, 'i', $vidId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $text, $xPos, $yPos, $time, $labelId);
        
    $texts = [];
    $xPositions = [];
    $yPositions = [];
    $times = [];
    $ids = [];
    
    while (mysqli_stmt_fetch($stmt)) {
        $texts[] = $text;
        $xPositions[] = $xPos;
        $yPositions[] = $yPos;
        $times[] = $time;
        $ids[] = $labelId;
    }
    
    $response["labelText"] = $texts;
    $response["xPos"] = $xPositions;
    $response["yPos"] = $yPositions;
    $response["time"] = $times;
    $response["labelId"] = $ids;
    
    $response["success"] = true;
        
    mysqli_stmt_close($stmt);
}



$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();
$secret = $_ENV['JWT_SECRET'];

header('Content-Type: application/json'); // Always return JSON
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

$response = ['success' => false, 'message' => '', 'labelText' => [], 'xPos' => [], 'yPos' => [], 'time' => [], 'labelId' => []];

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


if (!isset($_GET['id'])) {
    echo json_encode(["success" => false, "message" => "No video id"]);
    exit;
}

$vidId = intval($_GET['id']);


retrieveDetails($link, $response, $vidId);

echo(json_encode($response));
?>