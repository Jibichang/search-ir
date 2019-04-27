<?php
// header("Access-Control-Allow-Origin: http://localhost/rest-api-authentication-example/");
// header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");

header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/missingPersonObject.php';

$database = new Database();
$db = $database->getConnection();

$missing = new MissingPersons($db);

$data = json_decode(file_get_contents("php://input"));

$upload_path = "uploads/pic$data->id.jpg";

// $missing->image = $data->image;
$missing->path_img = $upload_path;
$missing->plost_id = $data->id;

// create the product
if($missing->upload()){
    file_put_contents($upload_path, base64_decode($data->path_img));
    // set response code - 201 created
    http_response_code(201);
    // tell the user
    echo json_encode(array("message" => $upload_path." upload image success "));
}
else{
// set response code - 400 bad request
http_response_code(400);
// tell the user
echo json_encode(array("message" => "Unable to create feedback. Data is incomplete."));
}
?>
