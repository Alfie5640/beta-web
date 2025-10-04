<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/auth.php';
include('../dbConnect.php');

 function sanitiseInputs(&$response) {
}




$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();
$secret = $_ENV['JWT_SECRET'];

header('Content-Type: application/json'); // Always return JSON
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: GET');

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


//sanitise Inputs
//if instructor (form input is not null)
//      check the climber exists =>
//      check the climber is connected to the isntructor
//check title exists =>
//get video Id for that title for that climber *entered* or *current*
//echo response and send Id in URL to video.php

?>