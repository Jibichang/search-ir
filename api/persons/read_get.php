<?php

  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Headers: access");
  header("Access-Control-Allow-Methods: GET");
  header("Access-Control-Allow-Credentials: true");
  header('Content-Type: application/json');

  include_once '../config/database.php';
  include_once '../objects/missingSearchIRObject.php';

  $database = new Database();
  $connection = $database->getConnection();

  $search = new MissingPersonsIR($connection);
  $data = json_decode(file_get_contents("php://input"));
  // $search->details = isset($_GET['d']) ? $_GET['d'] : die();

  $stmt = $search->read();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  $ir = $search->searchIR($search->read(), $data->query);

  // create array
  $search_arr = array(
    "ir" => $ir
  );
  print_r($ir);
  // echo json_decode($ir);
?>
