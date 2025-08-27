<?php
    include('../dbConnect.php');

    function sanitiseInputs($username, $pass) {
        global $response;
        
        if($username != filter_var($username, @FILTER_SANITIZE_STRING)) {
            http_response_code(400); // Bad Request
            $response['message'] = "Non-conforming characters in the username field. Please review and re-enter this field";
            $response['success'] = false;
            echo(json_encode($response));
            exit;
        } else {
            if($pass != filter_var($pass, @FILTER_SANITIZE_STRING)) {
                http_response_code(400); // Bad Request
                $response['message'] = "Non-conforming characters in the password field. Please review and re-enter this field";
                $response['success'] = false;
                echo(json_encode($response));
                exit;
            }
        }
    }


    function base64UrlEncode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    function makeJWT($payload, $secret) {
        
    $header = json_encode([
        'alg' => 'HS256', 
        'typ' => 'JWT'
    ]);
        
    $base64Header = base64UrlEncode($header);
    $base64Payload = base64UrlEncode(json_encode($payload));

    $signature = hash_hmac('sha256', $base64Header . "." . $base64Payload, $secret, true);
    $base64Signature = base64UrlEncode($signature);

    return $base64Header . "." . $base64Payload . "." . $base64Signature;
        
    }


    function createjwt($username, $role, $secret) {
    global $response;

    $payload = [
        "username" => $username,
        "role" => $role,
        "iat" => time(),            // issued at
        "exp" => time() + 3600      // expires in 1 hour
    ];

    $jwt =  makeJWT($payload, $secret);
    $response['token'] = $jwt;
    }



    function authenticateUser($link, $username, $pass, $secret) {
        global $response;
        
        $stmt = mysqli_prepare($link, "SELECT role, password_hash FROM EndUser WHERE username = ?");
        if ($stmt === false) {
            http_response_code(500);
            $response['message'] = "DB Error";
            return;
        }
        
        
        //Prepared statements
        
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $role, $storedPass);
        
        
        if(mysqli_stmt_fetch($stmt)) {
            mysqli_stmt_close($stmt);
            if(password_verify($pass, $storedPass)) {
                
                $response['success'] = true;
                $response['message'] = "Successful login";
                $response['role'] = $role;
                
                createjwt($username, $role, $secret);
                
            } else {
                http_response_code(401);
                $response['message'] = "Username or password incorrect";
            }      
        } else {
            http_response_code(404);
            $response['message'] = "Username or password incorrect";
            mysqli_stmt_close($stmt);
        }
        
    }


    require_once __DIR__ . '/../vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
    $dotenv->load();
    $secret = $_ENV['JWT_SECRET'];

    header('Content-Type: application/json'); // Always return JSON
    header('Access-Control-Allow-Origin: *'); 
    header('Access-Control-Allow-Methods: POST');

    // Get the raw POST body (JSON)
    $data = json_decode(file_get_contents('php://input'), true);

    $response = ['success' => false, 'message' => '', 'role' => '', 'token'=>''];

    $username = $data['username'];
    $pass = $data['password'];

    sanitiseInputs($username, $pass);
    authenticateUser($link, $username, $pass, $secret);

    echo(json_encode($response));

?>
