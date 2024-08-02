<?php

require_once 'Product.php';
require_once 'Database.php';

class Furniture extends Product
{
    private $height;
    private $width;
    private $length;
    private $conn;

    
    public function __construct($conn, $sku, $name, $price, $height, $width, $length)
    {
        parent::__construct($sku, $name, $price, 'Furniture');
        $this->conn = $conn; 
        $this->height = $height;
        $this->width = $width;
        $this->length = $length;
    }

    public function setHeight($height)
    {
        $this->height = $height;
    }

    public function setWidth($width)
    {
        $this->width = $width;
    }

    public function setLength($length)
    {
        $this->length = $length;
    }

    public function save()
    {
        try {
            $query = "INSERT INTO products (sku, name, price, type, height, width, length)
                      VALUES (:sku, :name, :price, 'Furniture', :height, :width, :length)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':sku', $this->sku);
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':price', $this->price);
            $stmt->bindParam(':height', $this->height);
            $stmt->bindParam(':width', $this->width);
            $stmt->bindParam(':length', $this->length);
            $stmt->execute();
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new Exception('SKU already exists. Please use another one.');
            } else {
                throw new Exception('An error occurred while saving the product: ' . $e->getMessage());
            }
        }
    }

    public function display()
    {
        return "Dimensions: {$this->height}x{$this->width}x{$this->length} cm";
    }
}

?>