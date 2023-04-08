<?php
class DVD extends Product
{
    private $size;
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
    public function constructorWithArguments0()
    {

    }
    public function constructorWithArguments1($product)
    {
        parent::__construct($product);
        $this->size = $product->size;
    }
    public function getProducts()
    {
        $rowCount = $this->getRowCount();
        if ($rowCount > 0) {
            $sql = "SELECT * FROM `product` WHERE `productType` = 'DVD'";
            $query = $this->connect()->prepare($sql);
            $query->execute();
            $result = $query->fetchAll();
            return $result;

        }
    }
    public function setProduct()
    {
        $msg = $this->dvdInputValidation($this->getSku(), $this->getName(), $this->getPrice(), $this->size);
        if ($msg == 1) {
            $sql = "INSERT INTO product (SKU, productName, price, productType, size) VALUES (?,?,?,?,?)  ";
            $query = $this->connect()->prepare($sql);
            $query->execute([$this->getSku(), $this->getName(), $this->getPrice(), $this->getProductType(), $this->size]);
            $response = ['status' => true, 'message' => 'product added successfully'];
            return $response;
        }
        else {
            $response = ['status' => false, 'message' => $msg];
            return $response;
        }
    }

    public function deleteProduct($SKU)
    {
        $placeholder = implode(',', array_fill(0, count($SKU), '?'));
        $sql = " DELETE FROM product WHERE productType = 'DVD' and SKU IN ($placeholder)";
        $query = $this->connect()->prepare($sql);
        $query->execute($SKU);
    }
    public function dvdInputValidation($SKU, $name, $price, $size)
    {
        $msg = Product::inputValidation($SKU, $name, $price);
        if ($msg === true) {
            if (empty($size)) {
                $msg = "Please enter size";
            } elseif (!filter_var($size, FILTER_VALIDATE_INT) || $size < 1 ||  strlen($size) > 5) {
                $msg = "Please, provide a valid size";
            }
        }
        return $msg;
    }
    public function getProductBySKU($sku){
        $sql = "SELECT * FROM `product` WHERE `productType` = 'DVD' and `sku` = '$sku'";
        $query = $this->connect()->prepare($sql);
        $query->execute();
        $result = $query->fetchAll();
        return $result;
    }
    function updateProduct($product){
        $sku = null;
        $name = $product->name;
        $price = $product->price;
        $size = $product->size;
        $msg = $this->DVDInputValidation($sku, $name, $price, $size);
            if ($msg === true) {
                $sku = $product->sku;
                $sql = "UPDATE `product` SET `productName` = ?, `Price` = ?, `size` = ? WHERE `SKU` = ? and `ProductType` = 'DVD'";
                $query = $this->connect()->prepare($sql);
                $query->execute([$name, $price, $size, $sku]);
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