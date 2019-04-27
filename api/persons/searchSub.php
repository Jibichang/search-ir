<?php
// header("Access-Control-Allow-Origin: http://localhost/rest-api-authentication-example/");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/database.php';
include_once '../config/core.php';
include_once '../objects/SelectThailand.php';
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
// initialize object
$dis = new SelectThailand($db);
$data = json_decode(file_get_contents("php://input"));
// get keywords
$dis->subdistrict = $data->subdistrict;

$stmt = $dis->selectSubDistrict();
$num = $stmt->rowCount();

if ($num >  0) {
  // set response code - 200 OK
  http_response_code(200);

  $missing_arr=array();
  $missing_arr["body"]=array();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
  {
    extract($row);
    $missing_item = array(
      "subdistrict"=> $subdistrict
    );

    array_push($missing_arr["body"], $missing_item);
  }

  echo json_encode($missing_arr);
}
else {
  // set response code - 404 Not found
  http_response_code(404);
  // tell the user no products found
  echo json_encode(
    array("message" => "Not found.")
  );
}
?>
