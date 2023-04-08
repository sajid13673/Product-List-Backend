<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
// header("Access-Control-Allow-Headers: Content-Type,
// Access-Control-Allow-Headers, Authorization, X-Requested-With");
include "classes\productController.class.php";
$data = json_decode(file_get_contents("php://input"), true);
  $obj = new ProductController();
  $t = $data[0];
  $p = $data[1];
  //$response = $obj->massDelete($t);
  //echo json_encode($response);
  // print_r($data);
  // print_r($data[0]->sku)

  
  //$obj = new ProductController();
  //$p = "Book" ;// $data->productType;
  //$s = "b";//$data->sku;
  $response = $obj->delete($p, $t);
  echo json_encode($response);
?>