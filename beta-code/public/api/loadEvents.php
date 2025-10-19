<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/auth.php';
include('../dbConnect.php');

function loadEvents($link, &$response, $userId) {
    $stmt = mysqli_prepare($link, "SELECT title, start_time, end_time, eventDate FROM CalendarEntry WHERE userId = ?");
    
    if($stmt === false) {
        http_response_code(500);
        $response['message'] = "DB Error";
        echo json_encode($response);
        exit;
    }
    
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $title, $start, $end, $date);
        
    $titles = [];
    $startTimes = [];
    $endTimes = [];
    $dates = [];
    
    while (mysqli_stmt_fetch($stmt)) {
        $titles[] = $title;
        $startTimes[] = $start;
        $endTimes[] = $end;
        $dates[] = $date;
    }
    
    $response["title"] = $titles;
    $response["startTime"] = $startTimes;
    $response["endTime"] = $endTimes;
    $response["eventDate"] = $dates;
    
    $response["success"] = true;
        
    mysqli_stmt_close($stmt);
}



$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();
$secret = $_ENV['JWT_SECRET'];

header('Content-Type: application/json'); // Always return JSON
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

$response = ['success' => false, 'message' => '', 'title' => [], 'startTime' => [], 'endTime' => [], 'eventDate' => []];

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

loadEvents($link, $response, $userId);

http_response_code(200);
echo(json_encode($response));
?>