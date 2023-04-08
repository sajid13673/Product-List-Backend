<?php
require 'DBConn.class.php';
require 'product.class.php';
require 'book.class.php';
require 'furniture.class.php';
require 'DVD.class.php';
class ProductController
{
    public function createProduct($productType, $product)
    {
        if(!empty($productType))
        {
        $newProduct = new $productType($product);
        $response = $newProduct->setProduct();
        return $response;}
        else{
            $response = ['status' => false, 'message' => "Please select a product type"];
        }
    }
    public function massDelete($sku)
    {
        if (!empty($sku)) {
            $products = [
                new Book(),
                new Furniture(),
                new DVD()
            ];
            foreach ($products as $Product) {
                $Product->deleteProduct($sku);
            }
            $response = "Products deleted succesfully";
        } else {
            $response = "Please select the products to delete";
        }
        return $response;
    }
    public function delete($productType, $sku){
        $newProduct = new $productType();
        $arrSku = [$sku];$newProduct->deleteProduct($arrSku);
        $response  = "Products deleted succesfully";
        return $response;
    }
    public function updateProduct($productType, $product){
        $obj = new $productType();
        $result = $obj->updateProduct($product);
        return $result;
    }
}
?>