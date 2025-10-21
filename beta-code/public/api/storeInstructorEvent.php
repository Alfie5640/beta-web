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

function getClimber($link, &$response, $userId, $climber) {

    $stmt = mysqli_prepare($link, "SELECT userId FROM EndUser WHERE username = ?");
    if ($stmt === false) {
        http_response_code(500);
        $response['message'] = "DB error (prepare 1)";
        exit;
    }

    mysqli_stmt_bind_param($stmt, 's', $climber);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $climberId);

    if (!mysqli_stmt_fetch($stmt)) {
        mysqli_stmt_close($stmt);
        http_response_code(404);
        $response['message'] = "Climber not found";
        echo json_encode($response);
        exit;
    }
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($link, "SELECT instructorId FROM climber_instructors WHERE climberId = ?");
    if ($stmt === false) {
        http_response_code(500);
        $response['message'] = "DB error";
        return null;
    }

    mysqli_stmt_bind_param($stmt, 'i', $climberId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $instructorId);

    $connected = false;
    while (mysqli_stmt_fetch($stmt)) {
        if ($instructorId == $userId) {
            $connected = true;
            break;
        }
    }
    mysqli_stmt_close($stmt);

    if (!$connected) {
        http_response_code(403);
        $response['message'] = "Climber not connected to instructor";
        echo json_encode($response);
        exit;
    }

    return $climberId;
}


function addEvent($link, &$response, $climberId, $title, $timeEnd, $timeStart, $date) {
    $stmt = mysqli_prepare($link, "INSERT INTO CalendarEntry (title, start_time, end_time, eventDate, userId) VALUES (?, ?, ?, ?, ?)");
    
     if($stmt) {
        
        mysqli_stmt_bind_param($stmt, 'ssssi', $title, $timeStart, $timeEnd, $date, $climberId);
        
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
$climber = $data['climber'];
$date = $data['date'];
$timeStart = $data['timeStart'];
$timeEnd = $data['timeEnd'];
$title = $data['title'];

sanitiseInputs($response, $title);
sanitiseInputs($response, $climber);

$climberId = getClimber($link, $response, $userId, $climber);

addEvent($link, $response, $climberId, $title, $timeEnd, $timeStart, $date);

echo(json_encode($response));
?>