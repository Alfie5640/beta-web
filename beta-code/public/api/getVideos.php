<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/auth.php';
include('../dbConnect.php');


function checkFavorite($link, $currentId) {
    
    $stmt = mysqli_prepare($link, "SELECT userId FROM Favourites WHERE videoId = ?");
    
    mysqli_stmt_bind_param($stmt, 'i', $currentId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    
    $favorited = mysqli_stmt_num_rows($stmt) > 0;
    mysqli_stmt_close($stmt);
    
    return $favorited;
}

function getVids(&$response, $userId, $link) {
    
    $stmt = mysqli_prepare($link, "SELECT title, grade, file_path, videoId FROM Videos WHERE userId = ?");
    
    if ($stmt === false) {
        http_response_code(500);
        $response['message'] = "DB error";
        return;
    }
    
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $title, $grade, $videos, $currentId);
    
    $titles=[];
    $grades=[];
    $video_paths = [];
    $favorites = [];
    $currentIds = [];
    $videoIds = [];
    
    while (mysqli_stmt_fetch($stmt)) {
        $titles[] = $title;
        $grades[] = $grade;
        $video_paths[] = $videos;
        $currentIds[] = $currentId;
        $videoIds[] = $currentId;
    }
    
    mysqli_stmt_close($stmt); //Must close statement before checking for favorites, as two prepared statements cannot be run at once
    
    foreach ($currentIds as $current) {
        $favorites[] = checkFavorite($link, $current);
    }
    
    $response['success'] = true;
    $response['titles'] = $titles;
    $response['grades'] = $grades;
    $response['videos'] = $video_paths;
    $response['favorites'] = $favorites;
    $response['videoIds'] = $videoIds;
}



$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();
$secret = $_ENV['JWT_SECRET'];

header('Content-Type: application/json'); // Always return JSON
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');



$response = ['success' => false, 'message' => '', 'videos' => [], 'titles' => [], 'grades' => [], 'favorites' => [], 'videoIds' => []];

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

getVids($response, $userId, $link);

echo json_encode($response);
?>