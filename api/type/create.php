<?php
// header("Access-Control-Allow-Origin: http://localhost/rest-api-authentication-example/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/typeObject.php';

$database = new Database();
$db = $database->getConnection();

$type = new Type ($db);

$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if(
    !empty($data->type_id) &&
    !empty($data->type_name) ){

    // set product property values
    $type->type_id = $data->type_id;
    $type->type_name = $data->type_name;
    // create the product
       if($type->create()){

           // set response code - 201 created
           http_response_code(201);

           // tell the user
           echo json_encode(array("message" => "Product was created."));
       }

       // if unable to create the product, tell the user
       else{

           // set response code - 503 service unavailable
           http_response_code(503);

           // tell the user
           echo json_encode(array("message" => "Unable to create product."));
       }
   }

   // tell the user data is incomplete
   else{

       // set response code - 400 bad request
       http_response_code(400);

       // tell the user
       echo json_encode(array("message" => "Unable to create product. Data is incomplete."));
   }
   ?>
