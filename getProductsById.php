<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
include "classes/productView.class.php";

$data = json_decode(file_get_contents("php://input"));

$sku = $data->data->sku;
$productType = $data->data->productType;

$obj = new ProductView();
$result = $obj->getProductsBySku($productType, $sku);
echo json_encode($result);
?>