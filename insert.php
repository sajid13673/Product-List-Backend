<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
// header("Access-Control-Allow-Headers: Content-Type,
// Access-Control-Allow-Headers, Authorization, X-Requested-With");
include "classes\productController.class.php";
$data = json_decode(file_get_contents("php://input"));

$productType = $data->productType;
$obj = new ProductController();
$response = $obj->createProduct($productType, $data);
echo json_encode($response);
