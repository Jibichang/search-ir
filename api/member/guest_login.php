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

$member->guest_email = $data->email;
$email_exists = $member->emailExists();

if($email_exists && password_verify($data->password, $member->guest_pass)){
  // set response code
  http_response_code(200);

  echo json_encode(
    array(
      "guest_id" => $member->guest_id,
      "name" => $member->guest_name,
      "email" => $member->guest_email,
      "password" => $member->guest_pass,
      "place" => $member->guest_place,
      "phone" => $member->guest_phone
    )
  );

}
else{
  http_response_code(401);
  echo json_encode(array("message" => "Login failed."));
}
?>
