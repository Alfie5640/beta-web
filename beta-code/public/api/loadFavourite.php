<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/auth.php';
include('../dbConnect.php');


function getFavFiles($link, &$response, $favouriteIds) {
    //Get the filepaths of the favourite videos
    
    foreach ($favouriteIds as $favourite) {
        $stmt = mysqli_prepare($link, "SELECT file_path FROM Videos WHERE videoId = ?");
    
        if ($stmt === false) {
            http_response_code(500);
            $response['message'] = "DB error";
            return;
        }
        
        mysqli_stmt_bind_param($stmt, 'i', $favourite);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $path);
        
        if (mysqli_stmt_fetch($stmt)) {
            array_push($response['favourites'], $path);
        }        
        mysqli_stmt_close($stmt);
    }
    
    $response['success'] = true;
    $response['message'] = "successful";
}


function loadFavourites($link, &$response, $userId) {
    $favouriteIds= [];
    //Get the ids of the favourite videos
    $stmt = mysqli_prepare($link, "SELECT videoId FROM Favourites WHERE userId = ?");
    
    if ($stmt === false) {
        http_response_code(500);
        $response['message'] = "DB error";
        return;
    }
    
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $videoId);
    
    while (mysqli_stmt_fetch($stmt)) {
        $favouriteIds[] = $videoId; 
    }
    mysqli_stmt_close($stmt);
    

    getFavFiles($link, $response, $favouriteIds);
}


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();
$secret = $_ENV['JWT_SECRET'];

header('Content-Type: application/json'); // Always return JSON
header('Access-Control-Allow-Origin: *'); // Adjust for your frontend domain
header('Access-Control-Allow-Methods: GET');


$response = ['success' => false, 'message' => '', 'favourites' => []];


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

loadFavourites($link, $response, $userId);


echo(json_encode($response));

?>