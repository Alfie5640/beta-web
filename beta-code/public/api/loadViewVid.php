<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/auth.php';
include('../dbConnect.php');



function retrieveDetails($link, &$response, $id) {
    $stmt = mysqli_prepare($link, "SELECT file_path, title FROM Videos WHERE videoId = ?");
    
    if($stmt === false) {
        http_response_code(500);
        $response['message'] = "DB Error";
        echo json_encode($response);
        exit;
    }
    
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $path, $title);
        
    if (mysqli_stmt_fetch($stmt)) {
        $response['path'] = $path;
        $response['title'] = $title;
        $response['success'] = true;
    }
    
    mysqli_stmt_close($stmt);
    
    
}




$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();
$secret = $_ENV['JWT_SECRET'];

header('Content-Type: application/json'); // Always return JSON
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

$response = ['success' => false, 'message' => '', 'path' => '', 'title' => ''];

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

$id = intval($_GET['id']);

retrieveDetails($link, $response, $id);


echo(json_encode($response));
?>