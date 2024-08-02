<?php

require_once 'Product.php';
require_once 'Database.php'; // Make sure to include Database class

class Book extends Product
{
    private $weight;

    public function __construct($sku, $name, $price, $weight)
    {
        parent::__construct($sku, $name, $price, 'Book');
        $this->weight = $weight;
    }

    public function getWeight()
    {
        return $this->weight;
    }

    public function save()
    {
        $conn = (new Database())->getConnection();

        $sku = $this->getSku();
        $name = $this->getName();
        $price = $this->getPrice();
        $weight = $this->getWeight();

        $sql = "INSERT INTO products (sku, name, price, type, weight) VALUES ('$sku', '$name', '$price', 'Book', '$weight')";

        if ($conn->query($sql) === TRUE) {
            echo "New Book product added successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    public function display()
    {
        return "Weight: {$this->weight} Kg";
    }
}

?>