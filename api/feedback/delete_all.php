<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/feedbackObject.php';

$database = new Database();
$connection = $database->getConnection();

$feedback = new feedback($connection);
$data = json_decode(file_get_contents("php://input"));
$feedback->guest_id = $data->guest_id;

// $stmt = $feedback->read();
// $count = $stmt->rowCount();

if($feedback->deleteAll()){
  // set response code - 200 OK
  http_response_code(200);

  echo json_encode(array("message" => "Delete success "));
}
else
{
  http_response_code(404);
  // tell the user no products found
  echo json_encode(array("message" => "Unable to delete feedback."));
}

?>
