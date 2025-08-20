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

    function authenticateUser($link, $username, $pass) {
        global $response;
        
        $stmt = mysqli_prepare($link, "SELECT role, password_hash FROM EndUser WHERE username = ?");
        if ($stmt === false) {
            http_response_code(500);
            $response['message'] = "DB Error";
            return;
        }
        
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $role, $storedPass);
        
        if(mysqli_stmt_fetch($stmt)) {
            mysqli_stmt_close($stmt);
            if(password_verify($pass, $storedPass)) {
                $response['success'] = true;
                $response['message'] = "Successful login";
                $response['role'] = $role;
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



    header('Content-Type: application/json'); // Always return JSON
    header('Access-Control-Allow-Origin: *'); // Adjust for your frontend domain
    header('Access-Control-Allow-Methods: POST');

    // Get the raw POST body (JSON)
    $data = json_decode(file_get_contents('php://input'), true);

    $response = ['success' => false, 'message' => '', 'role' => ''];

    $username = $data['username'];
    $pass = $data['password'];

    sanitiseInputs($username, $pass);
    authenticateUser($link, $username, $pass);

    echo(json_encode($response));

?>
