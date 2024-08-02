<?php
session_start();
require_once 'classes/Database.php';
require_once 'classes/Product.php';
require_once 'classes/DVD.php';
require_once 'classes/Book.php';
require_once 'classes/Furniture.php';

$db = new Database();
$conn = $db->getConnection();

$query = "SELECT * FROM products ORDER BY id";
$stmt = $conn->prepare($query);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Product List</title>
    <link rel="stylesheet"  href="./css/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .header{
            display:flex;
            justify-content:space-between;
        }
        .header-buttons{
            display:flex;
            justify-content:end;
            gap:20px;
            
        }

        h1 {
            text-align: center;
            margin: 20px 0;
        }

        button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius:5px;
        }

        form {
            max-width: 1200px;
            margin: 0 auto;
        }

        .product-list {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            padding: 20px;
            max-width: 1200px;  
            margin: 0 auto;     
        }

        .product-item {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 2px 2px 12px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            background-color: #fff;
        }

        .product-item p {
            margin: 10px 0;
        }

        .delete-checkbox {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    
    <form method="post" action="delete_products.php">
        <div class="header">
    <h1>Product List</h1>
    <div class="header-buttons">
    <button type="button" onclick="window.location.href='add_product.php'">ADD</button>
        <button type="submit">MASS DELETE</button></div></div>
        <hr>
        <div class="product-list">
            <?php foreach ($products as $product): ?>
                <div class="product-item">
                    <input type="checkbox" class="delete-checkbox" name="delete[]" value="<?= $product['id'] ?>">
                    <p>SKU: <?= $product['sku'] ?></p>
                    <p>Name: <?= $product['name'] ?></p>
                    <p>Price: <?= $product['price'] ?> $</p>
                    <p>
                        <?php
                        switch ($product['type']) {
                            case 'DVD':
                                echo "Size: " . $product['size'] . " MB";
                                break;
                            case 'Book':
                                echo "Weight: " . $product['weight'] . " Kg";
                                break;
                            case 'Furniture':
                                echo "Dimensions: " . $product['height'] . "x" . $product['width'] . "x" . $product['length'] . " cm";
                                break;
                        }
                        ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    </form>
</body>
</html>


