<?php
session_start();
require_once 'classes/Database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ids = $_POST['delete'];
    
    $db = new Database();
    $conn = $db->getConnection();

    foreach ($ids as $id) {
        $query = "DELETE FROM products WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    header("Location: list_products.php");
    exit();
}
?>

