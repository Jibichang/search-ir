<?php
// header("Access-Control-Allow-Origin: http://localhost/rest-api-authentication-example/");
header("Content-Type: application/json; charset=UTF-8");
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

// $upload_path = "uploads/$data->path.jpg"
// $missing->image = $data->image;
// $missing->path = $data->path;


// $missing->plost_id = $data->plost_id;
$missing->pname = $data->pname;
$missing->fname = $data->fname;
$missing->lname = $data->lname;
$missing->gender = $data->gender;
$missing->age = $data->age;
$missing->place = $data->place;
$missing->subdistrict = $data->subdistrict;
$missing->district = $data->district;
$missing->city = $data->city;
$missing->height = $data->height;
// $missing->weight = $data->weight;
$missing->shape = $data->shape;
$missing->hairtype = $data->hairtype;
$missing->haircolor = $data->haircolor;
$missing->skintone = $data->skintone;
$missing->upperwaist = $data->upperwaist;
$missing->uppercolor = $data->uppercolor;
$missing->lowerwaist = $data->lowerwaist;
$missing->lowercolor = $data->lowercolor;
$missing->detail_etc = $data->detail_etc;
$missing->special = $data->special;
$missing->type_id = $data->type_id;
$missing->guest_id = $data->guest_id;
$missing->status = $data->status;
$missing->reg_date = $data->reg_date;


// create the product
if($missing->create()){
    // set response code - 201 created
    http_response_code(201);
    $missing->getLastID();
    // tell the user
    echo json_encode(
      array("message" => "missing was created.",
            "plost_id" => $missing->plost_id)
    );
}
else{

// set response code - 400 bad request
http_response_code(400);

// tell the user
echo json_encode(array("message" => "Unable to create feedback. Data is incomplete."));
}
?>
