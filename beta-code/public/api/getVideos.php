<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/auth.php';
include('../dbConnect.php');


function getVids(&$response, $userId, $link) {
    
    $stmt = mysqli_prepare($link, "SELECT title, grade, file_path FROM Videos WHERE userId = ?");
    
    if ($stmt === false) {
        http_response_code(500);
        $response['message'] = "DB error";
        return;
    }
    
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $title, $grade, $videos);
    
    $titles=[];
    $grades=[];
    $video_paths = [];
    while (mysqli_stmt_fetch($stmt)) {
        $titles[] = $title;
        $grades[] = $grade;
        $video_paths[] = $videos;
    }
    
    mysqli_stmt_close($stmt);
    
    $response['success'] = true;
    $response['titles'] = $titles;
    $response['grades'] = $grades;
    $response['videos'] = $video_paths;
}



$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();
$secret = $_ENV['JWT_SECRET'];

header('Content-Type: application/json'); // Always return JSON
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');



$response = ['success' => false, 'message' => '', 'videos' => [], 'titles' => [], 'grades' => []];

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