<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/guestObject.php';

// get database connection
$database = new Database();
$db = $database->getConnection();
$member = new Guest($db);

$data = json_decode(file_get_contents("php://input"));
// decode jwt

$member->guest_id = $data->guest_id;
$member->guest_name = $data->name;
$member->guest_pass = $data->password;
// $member->guest_email = $data->email;
$member->guest_place = $data->place;
$member->guest_phone = $data->phone;

if($member->update() && $member->validPass($data->password)){
  http_response_code(200);
  // response in json format
  echo json_encode(array("message" => "User was updated."));
}
// message if unable to update user
else{
  http_response_code(401);
  echo json_encode(array("message" => "Access denied."));
}

?>
