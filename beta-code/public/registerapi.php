<?php
    include('../dbConnect.php');

    function sanitiseInputs($username, $pass) {
        global $response;
        if($username != filter_var($username, @FILTER_SANITIZE_STRING)) {
            $response['message'] = "Non-conforming characters in the username field. Please review and re-enter this field";
            $response['success'] = false;
            echo(json_encode($response));
            exit;
        } else {
            if($pass != filter_var($pass, @FILTER_SANITIZE_STRING)) {
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
        
        $stmt = mysqli_prepare($link, "INSERT INTO EndUser (username, `role`, password_hash) VALUES (?, ?, ?);");
        //username role hashedpass
        if ($stmt === false) {
            http_response_code(500);
            $response['message'] = "DB Error";
            return;
        }
        
        mysqli_stmt_bind_param($stmt, 'sss', $username, $role, $hashedPass);

        if (mysqli_stmt_execute($stmt)) {
            $response['success'] = true;
            $response['message'] = "User registered successfully";
        } else {
            http_response_code(500);
            $response['message'] = "DB Error: Failed to execute statement";
        }

        mysqli_stmt_close($stmt);
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
