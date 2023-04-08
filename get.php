<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
include "classes/productView.class.php";

$data = json_decode(file_get_contents("php://input"));
$obj = new ProductView();
$productList = $obj->displayProductDetails($data->key);
echo json_encode($productList);
?>