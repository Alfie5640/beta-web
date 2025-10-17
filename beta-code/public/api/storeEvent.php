<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/auth.php';
include('../dbConnect.php');

function sanitiseInputs(&$response, $title) {
    if($title != filter_var($title, @FILTER_SANITIZE_STRING)) {
        http_response_code(400); // Bad Request
        $response['message'] = "Non-conforming characters in the title field. Please review and re-enter this field";
        $response['success'] = false;
        echo(json_encode($response));
        exit;
    }
        
}


function addEvent($link, &$response, $userId, $title, $timeEnd, $timeStart, $date) {
    $stmt = mysqli_prepare($link, "INSERT INTO CalendarEntry (title, start_time, end_time, eventDate, userId) VALUES (?, ?, ?, ?, ?)");
    
     if($stmt) {
        
        mysqli_stmt_bind_param($stmt, 'ssssi', $title, $timeStart, $timeEnd, $date, $userId);
        
        if (mysqli_stmt_execute($stmt)) {
                $response['success'] = true;
                $response['message'] = "Event Added";
                
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


$userId = $payload['id'];
$date = $data['date'];
$timeStart = $data['timeStart'];
$timeEnd = $data['timeEnd'];
$title = $data['title'];

sanitiseInputs($response, $title);
addEvent($link, $response, $userId, $title, $timeEnd, $timeStart, $date);

echo(json_encode($response));
?>