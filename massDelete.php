<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
include "classes\productController.class.php";
$data = json_decode(file_get_contents("php://input"), true);
  $obj = new ProductController();
  $response = $obj->massDelete($data);
  echo json_encode($response);