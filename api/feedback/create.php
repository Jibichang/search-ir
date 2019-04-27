<?php
// header("Access-Control-Allow-Origin: http://localhost/rest-api-authentication-example/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/feedbackObject.php';

$database = new Database();
$db = $database->getConnection();

$feedback = new Feedback($db);

$data = json_decode(file_get_contents("php://input"));

$feedback->guest_id = $data->guest_id;
$feedback->id = $data->id;

// create the product
if(!empty($data->guest_id) && !empty($data->id)){
    $feedback->create();
    // set response code - 201 created
    http_response_code(201);

    // tell the user
    echo json_encode(array("message" => "feedback was created."));
}
else{

// set response code - 400 bad request
http_response_code(400);

// tell the user
echo json_encode(array("message" => "Unable to create feedback. Data is incomplete."));
}
?>
