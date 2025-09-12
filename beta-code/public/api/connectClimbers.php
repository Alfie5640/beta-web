<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/auth.php';
include('../dbConnect.php');

 function sanitiseInputs(&$response, $climberToAdd) {
    if($climberToAdd != filter_var($climberToAdd, @FILTER_SANITIZE_STRING)) {
        http_response_code(400); // Bad Request
        $response['message'] = "Non-conforming characters in the username field. Please review and re-enter this field";
        $response['success'] = false;
        echo(json_encode($response));
        exit;
    } 
}


function checkExists(&$response, $link, $climberId, $userId) {
    $stmt = mysqli_prepare($link, "SELECT 1 FROM climber_instructors WHERE climberId = ? AND instructorId = ?");
    
    if($stmt === false) {
        http_response_code(500);
        $response['message'] = "DB Error";
        echo json_encode($response);
        exit;
    }
    
    mysqli_stmt_bind_param($stmt, 'ii', $climberId, $userId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    $exists = mysqli_stmt_num_rows($stmt) > 0;

    mysqli_stmt_close($stmt);
    return $exists;
}



function lookUpId(&$response, $link, $climberToAdd) {
    $stmt = mysqli_prepare($link, "SELECT userId FROM EndUser WHERE username = ?");
    
    if($stmt === false) {
        http_response_code(500);
        $response['message'] = "DB Error";
        echo json_encode($response);
        exit;
    }
    
    mysqli_stmt_bind_param($stmt, 's', $climberToAdd);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $climberId);
    
    if (!mysqli_stmt_fetch($stmt)) {
        http_response_code(404);
        $response['message'] = "Climber not found";
        echo json_encode($response);
        exit;
    }
    
    mysqli_stmt_close($stmt);
    return $climberId;
}

function AddClimber(&$response, $link, $climberId, $userId) {
    $stmt = mysqli_prepare($link, "INSERT INTO climber_instructors (instructorId, climberId) VALUES (?,?)");
    
    if ($stmt === false) {
        http_response_code(500);
        $response['message'] = "DB error";
        return;
    }
    mysqli_stmt_bind_param($stmt, 'ii', $userId, $climberId);
        
    if (mysqli_stmt_execute($stmt)) {
        $response['message'] = "Climber added successfully";
        $response['success'] = true;
    } else {
            http_response_code(500);
            $response['message'] = "DB Error: Failed to execute statement";
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

//Verifies/sanitises inputs and inserts data in database
$climberToAdd = $data['climberName'];
$userId = $payload['id'];

sanitiseInputs($response, $climberToAdd);

$climberId = lookUpId($response, $link , $climberToAdd);

$alreadyExists = checkExists($response, $link, $climberId, $userId);

if (!$alreadyExists) {
    AddClimber($response, $link, $climberId, $userId);
} else {
    $response['message'] = "Username already connected to current instructor";
}
echo(json_encode($response));

?>