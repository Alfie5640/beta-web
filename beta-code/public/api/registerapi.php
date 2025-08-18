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


    function addUser($link, $username, $pass, $role) {
        global $response;
        $hashedPass = password_hash($pass, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO EndUser (username, `role`, password_hash) VALUES ('$username', '$role', '$hashedPass');";
        
        if (!mysqli_query($link, $sql)) {
            http_response_code(500); // Internal Server Error
            $response['message'] = "DB Error";
        } else {
            http_response_code(201); // Created
            $response['success'] = true;
            $response['message'] = "User registered successfully";
            
        }
    }


//---------------------------------


    header('Content-Type: application/json'); // Always return JSON
    header('Access-Control-Allow-Origin: *'); // Adjust for your frontend domain
    header('Access-Control-Allow-Methods: POST');

// Get the raw POST body (JSON)
    $data = json_decode(file_get_contents('php://input'), true);

    $response = ['success' => false, 'message' => ''];
    

    $username = $data['username'];
    $role = $data['role'];
    $pass = $data['password'];

    sanitiseInputs($username, $pass);

    addUser($link, $username, $pass, $role);

    echo(json_encode($response));

?>
