<?php
  header("Content-Type: application/json; charset=UTF-8");
  header("Access-Control-Max-Age: 3600");
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  include_once '../config/database.php';
  include_once '../objects/missingPersonObject.php';

  $database = new Database();
  $connection = $database->getConnection();
  $missing = new MissingPersons($connection);

  $stmt = $missing->read();
  // echo json_encode($stmt);
  // $num = $stmt->rowCount();

  if (!empty($stmt)) {
    // set response code - 200 OK
    http_response_code(200);
    // show products data
    echo json_encode($stmt);
  }
  else {
    // set response code - 404 Not found
    http_response_code(404);
    // tell the user no products found
    echo json_encode(
      array("message" => "No person found.")
    );
  }
?>
