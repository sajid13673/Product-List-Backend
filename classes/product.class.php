<?php
abstract class Product extends DBConn
{
    private $sku;
    private $name;
    private $price;
    private $productType;

    function __construct($product)
    {
        $this->sku = $product->sku;
        $this->name = $product->name;
        $this->price = $product->price;
        $this->productType = $product->productType;
    }
    abstract protected function getProducts();
    abstract protected function deleteproduct($sku);
    abstract protected function setProduct();
    abstract protected function getProductBySKU($sku);
    abstract protected function updateProduct($product);
    protected function getAvailableSkus()
    {
        $sql = "SELECT SKU FROM product";
        $query = $this->connect()->prepare($sql);
        $query->execute();
        $skuArr = $query->fetchAll();
        return $skuArr;
    }
    private function skuDuplication($sku){
        $skuArr = $this->getAvailableSkus();
        $skuLower = strtolower($sku);
        foreach ($skuArr as $row) {
            $id = $row['SKU'];
            $idLower = strtolower($id);
            if ($skuLower == $idLower) {
                return true;
            }
        }
    }
    private function SKUValidation($sku)
    {
        $duplicate = $this->skuDuplication($sku);
        if ($sku === null) {
            return true;
        } else {
            if (empty($sku)) {
                $msg = "Please enter SKU";
            } elseif (!preg_match("/^([A-Za-z 0-9]+)$/", $sku) ||  strlen($sku) > 100) {
                $msg = "Please, provide a valid SKU";
            } elseif ($duplicate === true) {
                $msg = "SKU already exists, Please enter a different SKU";
            }
            else{
                return true;
            }
            return $msg;
        }
    }
    public function FractionalDigitsLength($value){
        $arr = (explode(".",$value));
        $count = strlen($arr[1]);
        return $count;
    }
    public function IntegralDigitsLength($value){
        $arr = (explode(".",$value));
        $count = strlen($arr[0]);
        return $count;
    }
    private function priceLengthValidation($price){
        if(filter_var($price, FILTER_VALIDATE_INT)){
            if(strlen($price) > 7){
                return false;
            }
        }
        elseif(filter_var($price, FILTER_VALIDATE_FLOAT)){
            if($this->FractionalDigitsLength($price) > 2 || $this->IntegralDigitsLength($price) > 7){
                return false;
            }
        }
        else{
            return true;
        }
    }

    protected function inputValidation($sku, $name, $price)
    {

        $msg = $this->SKUValidation($sku);
        if($msg === true){   
            if (empty($name)) {
                $msg = "Please enter name";
            }
            elseif (!preg_match("/^([A-Za-z 0-9]+)$/", $name) ||  strlen($name) > 100) {
                $msg = "Please, provide a valid name";
            }
            elseif (empty($price)) {
                $msg = "Please enter price";
            }
            elseif (!filter_var($price, FILTER_VALIDATE_FLOAT) || $price < 0.1 || $this->priceLengthValidation($price) === false) {
                $msg = "Please, provide a valid Price";
            }
            else{
                return $msg = true;
            }
        }
        return $msg;
    }
    protected function getRowCount()
    {
        $sql = "SELECT * FROM product";
        $query = $this->connect()->prepare($sql);
        $query->execute();
        $rowCount = $query->rowCount();
        return $rowCount;
    }
    public function getSku()
    {
        return $this->sku;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getPrice()
    {
        return $this->price;
    }
    public function getProductType()
    {
        return $this->productType;
    }
}
