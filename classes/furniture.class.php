<?php
class Furniture extends Product
{
    private $height;
    private $width;
    private $length;
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
        $this->height = $product->height;
        $this->width = $product->width;
        $this->length = $product->length;
    }
    public function constructorWithArguments0()
    {
    }
    public function getProducts()
    {
        $rowCount = $this->getRowCount();
        if ($rowCount > 0) {
            $sql = "SELECT * FROM `product` WHERE `productType` = 'Furniture'";
            $query = $this->connect()->prepare($sql);
            $query->execute();
            $result = $query->fetchAll();
            return $result;
        }
    }

    public function setProduct()
    {
        $msg = $this->furnitureInputValidation($this->getSku(), $this->getName(), $this->getPrice(), $this->height, $this->width, $this->length);
        if ($msg == 1) {
            $sql = "INSERT INTO product (SKU, productName, price, productType, height, width, length) VALUES (?,?,?,?,?,?,?)  ";
            $query = $this->connect()->prepare($sql);
            $query->execute([$this->getSku(), $this->getName(), $this->getPrice(), $this->getProductType(), $this->height, $this->width, $this->length]);
            $response = ['status' => true, 'message' => 'product added successfully'];
            return $response;
        }
        else {
            $response = ['status' => false, 'message' => $msg];
            return $response;
        }
    }

    public function deleteProduct($sku)
    {
        $placeholder = implode(',', array_fill(0, count($sku), '?'));
        $sql = " DELETE FROM product WHERE productType = 'Furniture' and SKU IN ($placeholder)";
        $query = $this->connect()->prepare($sql);
        $query->execute($sku);
    }
    public function furnitureInputValidation($SKU, $name, $price, $height, $width, $length)
    {
        $msg = Product::inputValidation($SKU, $name, $price);
        if ($msg === true) {
            if (empty($height)) {
                $msg = "Please enter height";
            }
            elseif (!filter_var($height, FILTER_VALIDATE_INT) || $height < 1 ||  strlen($height) > 5) {
                $msg = "Please, provide a valid height";
            }
            elseif (empty($width)) {
                $msg = "Please enter width";
            }
            elseif (!filter_var($width, FILTER_VALIDATE_INT) || $width < 1 ||  strlen($width) > 5) {
                $msg = "Please, provide a valid width";
            }
            elseif (empty($length)) {
                $msg = "Please enter length";
            }
            elseif (!filter_var($length, FILTER_VALIDATE_INT) || $length < 1 ||  strlen($length) > 5) {
                $msg = "Please, provide a valid length";
            }
        }
        return $msg;
    }

    public function getProductBySKU($sku){
        $sql = "SELECT * FROM `product` WHERE `productType` = 'Furniture' and `sku` = '$sku'";
        $query = $this->connect()->prepare($sql);
        $query->execute();
        $result = $query->fetchAll();
        return $result;
    }
    function updateProduct($product){
        $sku = null;
        $name = $product->name;
        $price = $product->price;
        $height = $product->height;
        $width = $product->width;
        $length = $product->length;
        $msg = $this->furnitureInputValidation($sku, $name, $price, $height, $width, $length);
            if ($msg === true) {
                $sku = $product->sku;
                $sql = "UPDATE `product` SET `productName` = ?, `Price` = ?, `height` = ?, `width` = ?, `length` = ? WHERE `SKU` = ? and `ProductType` = 'Furniture'";
                $query = $this->connect()->prepare($sql);
                $query->execute([$name, $price, $height, $width, $length, $sku]);
                $response = ['status' => true, 'message' => 'product updated successfully'];
                    return $response;
        }
        else {
            $response = ['status' => false, 'message' => $msg];
            return $response;
        }
    }
}
?>