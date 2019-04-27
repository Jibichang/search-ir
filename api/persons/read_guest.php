<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once '../config/database.php';
include_once '../objects/missingPersonObject.php';

$database = new Database();
$db = $database->getConnection();
// initialize object
$missing = new MissingPersons($db);
$data = json_decode(file_get_contents("php://input"));
$missing->guest_id = $data->guest_id;

$stmt = $missing->guest_id();
$rowCount = $stmt->rowCount();

if($rowCount > 0){
  $missing_arr=array();
  $missing_arr["body"]=array();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
  {
    extract($row);
    $missing_item = array(
      "id"=> $plost_id,
      "pname"=> $pname,
      "fname"=> $fname,
      "lname"=> $lname,
      "gender"=> $gender,
      "age"=> $age,
      "place"=> $place,
      "subdistrict"=> $subdistrict,
      "district"=> $district,
      "city"=> $city,
      "height"=> $height,
      "weight"=> $weight,
      "shape"=> $shape,
      "hairtype"=> $hairtype,
      "haircolor"=> $haircolor,
      "skintone"=> $skintone,
      "upperwaist"=> $upperwaist,
      "uppercolor"=> $uppercolor,
      "lowerwaist"=> $lowerwaist,
      "lowercolor"=> $lowercolor,
      "detail_etc"=> $detail_etc,
      "special"=> $special,
      "type_id"=> $type_id,
      "guest_id"=> $guest_id,
      "status"=> $status,
      "reg_date"=> $reg_date,
      "path_img"=> $path_img
      // "ss"=>$this->feedback_array[$key_plus]
    );
    array_push($missing_arr["body"], $missing_item);
    // array_push($sim_result, $row["detail_etc"]); // detail (doc)
  }
  http_response_code(200);
  echo json_encode($missing_arr, JSON_UNESCAPED_UNICODE);
  // echo $data->guest_id;
}else {
  // set response code - 404 Not found
  http_response_code(404);
  // tell the user no products found
  echo json_encode(
    array("message" => "No person found.")
  );
}


?>
