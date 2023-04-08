<?php
require 'DBConn.class.php';
require 'product.class.php';
require 'book.class.php';
require 'furniture.class.php';
require 'DVD.class.php';
class ProductView
{

    public function displayProductDetails($key)
    {

        $book = new Book();
        $furniture = new Furniture();
        $dvd = new DVD();
        $bookArr = $book->getProducts();
        $furnitureArr = $furniture->getProducts();
        $dvdArr = $dvd->getProducts();

        if (!empty($bookArr || $dvdArr || $furnitureArr)) {
            $productArr = array_merge($bookArr, $furnitureArr, $dvdArr);
            $column = array_column($productArr, $key);
            array_multisort($column, SORT_ASC | SORT_NATURAL | SORT_FLAG_CASE, $productArr);
            $productList = ['status' => true, 'list' => $productArr];
        }
        else {
            $productList = ['status' => false];
        }
        return $productList;
    }
    public function getProductsBySku($productType, $sku){
        $newProduct = new $productType();
        $result = $newProduct->getProductBySKU($sku);
        return $result;
    }
}
?>