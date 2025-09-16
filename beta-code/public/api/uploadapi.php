<?php
    require_once __DIR__ . '/../vendor/autoload.php';
    require_once __DIR__ . '/auth.php';
    include ('../dbConnect.php');
    
    //functions here  ------------





    
    //----------------------------

    header('Content-Type: application/json'); // Always return JSON
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST');
    
    
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
    $dotenv->load();
    $secret = $_ENV['JWT_SECRET'];
    $response = ['success' => false, 'message' => ''];



    //JWT verification ------------
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



    //-----------------------------
    
    
    $userId = $payload['id'];
    $title = $_POST['videoTitle'] ?? null;
    $privacy = $_POST['privacy'] ?? null;
    $grade = $_POST['videoGrade'] ?? null;
    $date = date("Y-m-d");

    
    $targetDir = __DIR__ . "/../uploads/";
    $ext = pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION);
    $filename = uniqid("vid_", true) . "." . $ext;
    $targetFile = $targetDir . $filename;

    $filePath = "uploads/" . $filename;

    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
        $stmt = mysqli_prepare($link, "INSERT INTO Videos (userId, title, grade, filepath, privacy, upload_date) VALUES (?, ?, ?, ?, ?, ?)");
    
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'isssss', $userId, $title, $grade, $filePath, $privacy, $date);
            
            if (mysqli_stmt_execute($stmt)) {
                $response['success'] = true;
                $response['message'] = "File uploaded and saved to database.";
                
            } else {
                http_response_code(500);
                $response['message'] = "Database insert failed";
            }
            
        } else {
            http_response_code(500);
            $response['message'] = "DB Error";
        }
        
    } else {
        http_response_code(500);
        $response['message'] = "Error uploading file";
    }
    echo json_encode($response);

?>