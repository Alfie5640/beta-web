<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/auth.php';
include('../dbConnect.php');

    function sanitiseInputs(&$response, $climber, $video) {
        if($climber != filter_var($climber, @FILTER_SANITIZE_STRING)) {
            http_response_code(400); // Bad Request
            $response['message'] = "Non-conforming characters in the username field. Please review and re-enter this field";
            $response['success'] = false;
            echo(json_encode($response));
            exit;
        } else {
            if($video != filter_var($video, @FILTER_SANITIZE_STRING)) {
                http_response_code(400); // Bad Request
                $response['message'] = "Non-conforming characters in the password field. Please review and re-enter this field";
                $response['success'] = false;
                echo(json_encode($response));
                exit;
            }
        }
    }

    function checkStudent(&$response, $link, $climber, $userId) {
        $stmt = mysqli_prepare($link, "SELECT userId FROM EndUser WHERE role = 'climber' AND username = ?");
        if ($stmt === false) {
            http_response_code(500);
            $response['message'] = "DB Error";
            echo(json_encode($response));
            exit;
        }
        
        mysqli_stmt_bind_param($stmt, 's', $climber);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $climberFound);
        
        if(mysqli_stmt_fetch($stmt)) {
            $climberId = $climberFound;
            mysqli_stmt_close($stmt);  
            
            $sql = mysqli_prepare($link, "SELECT 1 FROM climber_instructors WHERE climberId = ? AND instructorId = ?");
            
            if ($sql === false) {
                http_response_code(500);
                $response['message'] = "DB Error";
                echo(json_encode($response));
                exit;
            }
            
            mysqli_stmt_bind_param($sql, 'ii', $climberId, $userId);
            mysqli_stmt_execute($sql);
            mysqli_stmt_store_result($sql);

            if(mysqli_stmt_num_rows($sql) > 0) {
                mysqli_stmt_close($sql);
                return $climberId;
            } else {
                mysqli_stmt_close($sql);
                http_response_code(404);
                $response['message'] = "Climber Not Connected to Instructor";
                echo(json_encode($response));
                exit;
            }
            
        } else {
            http_response_code(404);
            $response['message'] = "Climber Not Found";
            mysqli_stmt_close($stmt);
            echo(json_encode($response));
            exit;
        }
    }


    function getVideoId(&$response, $link, $climberId, $video) {
        $stmt = mysqli_prepare($link, "SELECT videoId FROM Videos WHERE userId = ? AND title = ? AND privacy = 'public'");
        if ($stmt === false) {
            http_response_code(500);
            $response['message'] = "DB Error";
            echo(json_encode($response));
            exit;
            }
        
        mysqli_stmt_bind_param($stmt, 'is', $climberId, $video);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $videoFound);
        
        if(mysqli_stmt_fetch($stmt)) {

            $videoId = $videoFound;
            mysqli_stmt_close($stmt);
            return $videoId;
            
        } else {
            http_response_code(404);
            $response['message'] = "Video not found for this user or is private";
            mysqli_stmt_close($stmt);
            echo(json_encode($response));
            exit;
        }
    }

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();
$secret = $_ENV['JWT_SECRET'];

header('Content-Type: application/json'); // Always return JSON
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: POST');

$response = ['success' => false, 'message' => '', 'vidId' => null];

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
$climber = $data['climberName'];
$video = $data['videoTitle'];

sanitiseInputs($response, $climber, $video);
//sanitise Inputs

if (!empty($climber)) {
    $climberId = checkStudent($response, $link, $climber, $userId);
} else {
    $climberId = $userId;
}

$videoId = getVideoId($response, $link, $climberId, $video);

$response['vidId'] = $videoId;
$response['success'] = true;
echo(json_encode($response));
?>
