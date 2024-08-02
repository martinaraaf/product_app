<?php

class DVD extends Product {
    private $size;

    public function __construct($sku, $name, $price, $size) {
        parent::__construct($sku, $name, $price, 'DVD');
        $this->size = $size;
    }

    public function getSize() {
        return $this->size;
    }

    public function save() {
        $conn = (new Database())->getConnection(); 
        $sku = $this->getSku();
        $name = $this->getName();
        $price = $this->getPrice();
        $size = $this->getSize();
    
        $sql = "INSERT INTO products (sku, name, price, type, size) VALUES ('$sku', '$name', '$price', 'DVD', '$size')";
        if ($conn->query($sql) === TRUE) {
            echo "New DVD product added successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    public function display() {
        return "Size: {$this->size} MB";
    }
}
?>

