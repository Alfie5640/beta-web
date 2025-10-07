<?php
    require_once __DIR__ . '/../vendor/autoload.php';
    require_once __DIR__ . '/auth.php';
    include ('../dbConnect.php');
    
    function sanitiseInputs(&$response, $comment) {
        if($comment != filter_var($comment, @FILTER_SANITIZE_STRING)) {
            http_response_code(400); // Bad Request
            $response['message'] = "Non-conforming characters in the comment field. Please review and re-enter this field";
            $response['success'] = false;
            echo(json_encode($response));
            exit;
        }
    }


    function insertComment($link, &$response, $comment, $userId, $vidId) {
        $stmt = mysqli_prepare($link, "INSERT INTO Comments (userId, videoId, commentText) VALUES (?, ?, ?)");
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'iis', $userId, $vidId, $comment);
            
            if (mysqli_stmt_execute($stmt)) {
                $response['success'] = true;
                $response['message'] = "Comment Submitted.";
                
            } else {
                http_response_code(500);
                $response['message'] = "Database insert failed";
            }
            
        } else {
            http_response_code(500);
            $response['message'] = "DB Error";
        }
    }


    header('Content-Type: application/json'); // Always return JSON
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST');
    
    
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
    $dotenv->load();
    $secret = $_ENV['JWT_SECRET'];
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
    
    if (!isset($_GET['id'])) {
    echo json_encode(["success" => false, "message" => "No video id"]);
    exit;
    }
    $data = json_decode(file_get_contents('php://input'), true);

    $comment = $data['comment'];
    $userId = $payload['id'];
    $vidId = intval($_GET['id']);

    if (strlen($comment) > 250) {
        http_response_code(400);
        $response['message'] = "Comment too long - max 250 characters";
        echo json_encode($response);
        exit;
    }

    sanitiseInputs($response, $comment);
    insertComment($link, $response, $comment, $userId, $vidId);

echo(json_encode($response));
?>