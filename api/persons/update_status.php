<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object files
include_once '../config/database.php';
include_once '../objects/missingPersonObject.php';

// get database connection
$database = new Database();
$db = $database->getConnection();
$missing = new MissingPersons ($db);

$data = json_decode(file_get_contents("php://input"));

$missing->plost_id = $data->plost_id;

if($missing->updateStatus()){
    // set response code - 200 ok
    http_response_code(200);
    // tell the user
    echo json_encode(array("message" => "Status was updated."));
}
// if unable to update the product, tell the user
else{
    // set response code - 503 service unavailable
    http_response_code(503);
    // tell the user
    echo json_encode(array("message" => "Unable to update status."));
}
?>
