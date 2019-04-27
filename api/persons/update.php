<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object files
include_once '../config/database.php';
include_once '../objects/missingPersonObject.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare product object
$missing = new MissingPersons ($db);

// get id of product to be edited
$data = json_decode(file_get_contents("php://input"));

// set ID property of product to be edited

$missing->plost_id = $data->plost_id;

// set product property values
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


// update the product
if($missing->update() ){

    // set response code - 200 ok
    http_response_code(200);

    // tell the user
    echo json_encode(array("message" => "Product was updated."));
}

// if unable to update the product, tell the user
else{

    // set response code - 503 service unavailable
    http_response_code(503);

    // tell the user
    echo json_encode(array("message" => "Unable to update product."));
}
?>
