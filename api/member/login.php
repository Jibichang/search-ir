<?php
// header("Access-Control-Allow-Origin: http://localhost/rest-api-authentication-example/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/memberObject.php';

$database = new Database();
$db = $database->getConnection();

$member = new Member($db);

$data = json_decode(file_get_contents("php://input"));

// echo json_encode(
//   array(
//     "email" => $data->email,
//     "password" => $data->password
//   )
// );

$member->guest_email = $data->email;
$email_exists = $member->emailExists();

include_once '../config/core.php';
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

if($email_exists && password_verify($data->password, $member->guest_pass)){

  $token = array(
    "iss" => $iss,
    "aud" => $aud,
    "iat" => $iat,
    "nbf" => $nbf,
    "data" => array(
      "id" => $member->guest_id,
      "name" => $member->guest_name,
      "email" => $member->guest_email,
      "password" => $member->guest_pass
    )
  );

  // set response code
  http_response_code(200);

  // generate jwt
  $jwt = JWT::encode($token, $key);
  echo json_encode(
    array(
      "message" => "Successful login.",
      "jwt" => $jwt
    )
  );

}
else{
  http_response_code(401);
  echo json_encode(array("message" => "Login failed."));
}
?>
