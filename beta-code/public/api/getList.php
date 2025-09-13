<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/auth.php';
include('../dbConnect.php');


function lookupUser(&$response, $link, $climberId) {
    $stmt = mysqli_prepare($link, "SELECT username FROM EndUser WHERE userId = ?");
    
    if ($stmt === false) {
        http_response_code(500);
        $response['message'] = "DB error";
        return;
    }
    
    mysqli_stmt_bind_param($stmt, 'i', $climberId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $username);
    
    if(!mysqli_stmt_fetch($stmt)) {
        http_response_code(404);
        $response['message'] = "Climber not found";
        echo json_encode($response);
        exit;
    }
    
    mysqli_stmt_close($stmt);
    return $username;
}




function getList(&$response, $link, $userId) {
    $stmt = mysqli_prepare($link, "SELECT climberId FROM climber_instructors WHERE instructorId = ?");
    
    if ($stmt === false) {
        http_response_code(500);
        $response['message'] = "DB error";
        return;
    }
    
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $climberId);
    
    $climberIds = [];
    while (mysqli_stmt_fetch($stmt)) {
        $climberIds[] = $climberId;
    }
    
    mysqli_stmt_close($stmt);

    $usernames = [];
    foreach ($climberIds as $id) {
        $usernames[] = lookupUser($response, $link, $id);
    }
    
    $response['success'] = true;
    $response['climbers'] = $usernames;
}


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();
$secret = $_ENV['JWT_SECRET'];

header('Content-Type: application/json'); // Always return JSON
header('Access-Control-Allow-Origin: *'); // Adjust for your frontend domain
header('Access-Control-Allow-Methods: GET');



$response = ['success' => false, 'message' => '', 'climbers' => []];

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


//Assigns a list of pupils to corresponding place in $response
$userId = $payload['id'];
getList($response, $link, $userId);

echo(json_encode($response));
?>