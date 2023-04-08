<?php
class Book extends Product
{
    private $weight;
    public function __construct()
    {
        $arguments = func_get_args();
        $numberOfArguments = func_num_args();

        if (method_exists($this, $function =
            'constructorWithArguments' . $numberOfArguments)) {
            call_user_func_array(
                    array($this, $function), $arguments);
        }
    }
    public function constructorWithArguments1($product)
    {
        parent::__construct($product);
        $this->weight = $product->weight;
    }
    public function constructorWithArguments0()
    {
    }
    public function getProducts()
    {
        $rowCount = $this->getRowCount();
        if ($rowCount > 0) {
            $sql = "SELECT * FROM `product` WHERE `productType` = 'Book'";
            $query = $this->connect()->prepare($sql);
            $query->execute();
            $result = $query->fetchAll();
            return $result;

        }
    }

    public function setProduct()
    {
            $msg = $this->bookInputValidation($this->getSku(), $this->getName(), $this->getPrice(), $this->weight);
            if ($msg == 1) {
                $sql = "INSERT INTO product (SKU, productName, price, productType, weight) VALUES (?,?,?,?,?)  ";
                $query = $this->connect()->prepare($sql);
                $query->execute([$this->getSku(), $this->getName(), $this->getPrice(), $this->getProductType(), $this->weight]);

                $response = ['status' => true, 'message' => 'product added successfully'];
            return $response;
        }
        else {
            $response = ['status' => false, 'message' => $msg];
            return $response;
        }
    }
    private function weightLengthValidation($weight){
        if(filter_var($weight, FILTER_VALIDATE_INT)){
            if(strlen($weight) > 3){
                return false;
            }
        }
        elseif(filter_var($weight, FILTER_VALIDATE_FLOAT)){
            if($this->FractionalDigitsLength($weight) > 2 || $this->IntegralDigitsLength($weight) > 3){
                return false;
            }
        }
        else{
            return true;
        }
    }
    public function bookInputValidation($SKU, $name, $price, $weight)
    {
        $msg = Product::inputValidation($SKU, $name, $price);
        if ($msg === true){
            if (empty($weight)){
                $msg = "Please enter weight";
            }
            elseif (!filter_var($weight, FILTER_VALIDATE_FLOAT) || $weight < 0.1 || $this->weightLengthValidation($weight) === false) {
                $msg = "Please, provide a valid weight";
            }
        }
        return $msg;
    }

    public function deleteProduct($SKU)
    {
        $placeholder = implode(',', array_fill(0, count($SKU), '?'));
        $sql = " DELETE FROM product WHERE productType = 'Book' and SKU IN ($placeholder)";
        $query = $this->connect()->prepare($sql);
        $query->execute($SKU);
    }

    public function getProductBySKU($sku){
        $sql = "SELECT * FROM `product` WHERE `productType` = 'Book' and `sku` = '$sku'";
        $query = $this->connect()->prepare($sql);
        $query->execute();
        $result = $query->fetchAll();
        return $result;
    }
    public function updateProduct($product){
        $sku = null;
        $name = $product->name;
        $price = $product->price;
        $weight = $product->weight;
        $msg = true;
        $msg = $this->bookInputValidation($sku, $name, $price, $weight);
            if ($msg === true) {
        $sku = $product->sku;
        $sql = "UPDATE `product` SET `productName` = ?, `Price` = ?, `weight` = ? WHERE `SKU` = ? and `ProductType` = 'Book'";
        $query = $this->connect()->prepare($sql);
        $query->execute([$name, $price, $weight, $sku]);
        $response = ['status' => true, 'message' => 'product updated successfully'];
            return $response;
        }
        else {
            $response = ['status' => false, 'message' => $msg];
            return $response;
        }
    }

    //new try
    public function hello($productType, $sku){
        $s = $sku;
        return $s;
        
    }

}
?>