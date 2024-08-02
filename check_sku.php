<?php
session_start();
function checkIfSkuIsTaken($sku)
{
    $host = 'localhost';
    $db_name = 'product_db';
    $username = 'root';
    $password = '';
   
    $conn = new mysqli($host, $username, $password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT COUNT(*) FROM products WHERE sku = ?");
    $stmt->bind_param("s", $sku);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    // Return true if SKU is taken, false otherwise
    return $count > 0;
}

// Check if the SKU is taken
$skuToCheck = $_POST['sku'];
$isTaken = checkIfSkuIsTaken($skuToCheck);

if ($isTaken) {
    echo "SKU is already taken. Please choose another.";
} else {
    echo "SKU is available!";
}
