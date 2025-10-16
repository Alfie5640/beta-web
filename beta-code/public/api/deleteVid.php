<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/auth.php';
include('../dbConnect.php');

function deleteComments($link, &$response, $videoId) {
    $stmt = mysqli_prepare($link, "DELETE FROM Comments WHERE videoId = ?");
    mysqli_stmt_bind_param($stmt, 'i', $videoId);
    
    if (!mysqli_stmt_execute($stmt)) {
            http_response_code(500); // Internal Server Error
            $response['message'] = "DB Error";
            echo(json_encode($response));
            exit;
        }
        
    mysqli_stmt_close($stmt);
}

function deleteFavourites($link, &$response, $videoId) {
    $stmt = mysqli_prepare($link, "DELETE FROM Favourites WHERE videoId = ?");
    mysqli_stmt_bind_param($stmt, 'i', $videoId);
    
    if (!mysqli_stmt_execute($stmt)) {
            http_response_code(500); // Internal Server Error
            $response['message'] = "DB Error";
            echo(json_encode($response));
            exit;
        }
        
    mysqli_stmt_close($stmt);
}

function deleteLabels($link, &$response, $videoId) {
    $stmt = mysqli_prepare($link, "DELETE FROM Labels WHERE videoId = ?");
    mysqli_stmt_bind_param($stmt, 'i', $videoId);
    
    if (!mysqli_stmt_execute($stmt)) {
            http_response_code(500); // Internal Server Error
            $response['message'] = "DB Error";
            echo(json_encode($response));
            exit;
        }
        
    mysqli_stmt_close($stmt);
}

function deleteVideo($link, &$response, $videoId) {
    $stmt = mysqli_prepare($link, "DELETE FROM Videos WHERE videoId = ?");
    mysqli_stmt_bind_param($stmt, 'i', $videoId);
    
    if (!mysqli_stmt_execute($stmt)) {
            http_response_code(500); // Internal Server Error
            $response['message'] = "DB Error";
            echo(json_encode($response));
            exit;
    } else {
        http_response_code(200); // Deleted
        $response['success'] = true;
        $response['message'] = "Video Deleted";
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


$data = json_decode(file_get_contents('php://input'), true);
$videoId = $data['videoId'];


//Delete comments, favourites, labels then vids

deleteComments($link, $response, $videoId);
deleteFavourites($link, $response, $videoId);
deleteLabels($link, $response, $videoId);
deleteVideo($link, $response, $videoId);

echo(json_encode($response));

?>
