<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/auth.php';
include('../dbConnect.php');

function findNames($link, &$response, $ids, &$names) {
    $stmt = mysqli_prepare($link, "SELECT username, role FROM EndUser WHERE userId = ?");
    
    if ($stmt === false) {
        http_response_code(500);
        $response['message'] = "DB Error in findNames";
        echo json_encode($response);
        exit;
    }

    for ($i = 0; $i < count($ids); $i++) {
        mysqli_stmt_bind_param($stmt, 'i', $ids[$i]);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $name, $role);
        mysqli_stmt_fetch($stmt);
        
        $names[] = $name;
        $response["roles"][] = $role;
    }

    mysqli_stmt_close($stmt);
}

function loadComments($link, &$response, $vidId) {
    $stmt = mysqli_prepare($link, "SELECT userId, commentText FROM Comments WHERE videoId = ?");
    
    if ($stmt === false) {
        http_response_code(500);
        $response['message'] = "DB Error in loadComments";
        echo json_encode($response);
        exit;
    }

    mysqli_stmt_bind_param($stmt, 'i', $vidId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $userId, $comment);
    
    $ids = [];
    $comments = [];

    while (mysqli_stmt_fetch($stmt)) {
        $ids[] = $userId;
        $comments[] = $comment;
    }

    mysqli_stmt_close($stmt);

    $response["commentTexts"] = $comments;

    $names = [];
    findNames($link, $response, $ids, $names);

    $response["usernames"] = $names;
    $response["success"] = true;
}

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();
$secret = $_ENV['JWT_SECRET'];
$response = ['success' => false, 'message' => '', 'usernames' => [], 'commentTexts' => [], 'roles' => []];

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
loadComments($link, $response, $vidId);

echo json_encode($response);
?>