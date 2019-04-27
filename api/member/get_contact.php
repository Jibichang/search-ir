<?php
// header("Access-Control-Allow-Origin: http://localhost/rest-api-authentication-example/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/guestObject.php';

$database = new Database();
$db = $database->getConnection();

$member = new Guest($db);
$data = json_decode(file_get_contents("php://input"));

$member->guest_id = $data->guest_id;

if($member->contact()){
  // set response code
  http_response_code(200);

  echo json_encode(
    array(
      "name" => $member->guest_name,
      "email" => $member->guest_email,
      "phone" => $member->guest_phone
    )
  );

}
else{
  http_response_code(401);
  echo json_encode(array("message" => "Contact failed."));
}
?>
