<?php
session_start();
require_once 'classes/Database.php';
require_once 'classes/Product.php';
require_once 'classes/DVD.php';
require_once 'classes/Book.php';
require_once 'classes/Furniture.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $sku = $_POST['sku'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $type = $_POST['productType'];

    // Create a database connection
    $db = new Database();
    $conn = $db->getConnection();

    // Based on the product type, create the appropriate product object
    switch ($type) {
        case 'DVD':
            $size = $_POST['size'];
            $product = new DVD($sku, $name, $price, $size);
            break;
        case 'Book':
            $weight = $_POST['weight'];
            $product = new Book($sku, $name, $price, $weight);
            break;
        case 'Furniture':
            $height = $_POST['height'];
            $width = $_POST['width'];
            $length = $_POST['length'];
            $product = new Furniture($conn, $sku, $name, $price, $height, $width, $length);
            break;
        default:
            // Handle invalid product type
            echo "Invalid product type";
            exit();
    }

    // Save the product
    $product->save();

    // Redirect to the products list page
    header("Location: list_products.php");
    exit();
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>Add Product</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <script src="js/script.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        .header {
            display: flex;
            justify-content: space-between;
        }

        .header-buttons {
            display: flex;
            justify-content: end;
            gap: 20px;

        }

        h1 {
            text-align: center;
            margin: 20px 0;
        }

       .header button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }

        form {
            max-width: 1200px;
            margin: 0 auto;
        }

        .sku,
        .name,
        .price,
        .products-type,
        #productDetails,
        .furniture-length,
        .furniture-width,
        .furniture-height {
            margin-bottom: 1.5rem;
        }

        .inputs-container {
            margin-top: 3rem;
        }

        .sku label {
            margin-right: 40px;
        }

        .name label {
            margin-right: 30px;
        }

        .price label {
            margin-right: 12px;
        }

        .products-type select {
            padding: 5px 10px;
        }

        .products-type label {
            margin-right: 10px;
        }

        .furniture-height label,
        .furniture-length label {
            margin-right: 5px;
        }

        .furniture-width label {
            margin-right: 10px;
        }
        .sku button{
            border-radius: 5px;
            padding: 5px ;
            font-size: 10px;
        }
    </style>
</head>

<body>


    <form id="product_form" method="post" onsubmit="return validateForm()">
        <div class="header">
            <h1>add product</h1>
            <div class="header-buttons">
                <button type="submit" id="save" disabled>Save</button>
                <button type="button" onclick="cancelAddProduct()">Cancel</button>
            </div>

        </div>
        <hr>
        <div class="inputs-container">
            <div class="sku">
                <label for="sku">SKU</label>
                <input type="text" id="sku" name="sku">
                <button type="button" onclick="checkAvailability(event)">Check Availability</button>
                <div id="resultMessage"></div>
            </div>
            <div class="name">
                <label for="name">Name</label>
                <input type="text" id="name" name="name">
            </div>
            <div class="price">
                <label for="price">Price ($)</label>
                <input type="text" id="price" name="price" step="0.01">
            </div>
            <div class="products-type">
                <label for="productType">Product Type</label>
                <select id="productType" name="productType" onchange="showProductSpecificFields()">
                    <option value="">Select type</option>
                    <option value="DVD">DVD</option>
                    <option value="Book">Book</option>
                    <option value="Furniture">Furniture</option>
                </select>
            </div>
            <div id="productDetails">

            </div>


            <div id="notification"></div>
        </div>
    </form>

    <script>
        function showProductSpecificFields() {
            var productType = document.getElementById("productType").value;
            var productDetailsDiv = document.getElementById("productDetails");
            var notificationDiv = document.getElementById("notification");
            productDetailsDiv.innerHTML = "";

            if (productType === "DVD") {
                productDetailsDiv.innerHTML = `
                    <label for="size">Size (MB)</label>
                    <input type="text" id="size" name="size" required>
                `;
                notificationDiv.innerText = "Please provide size";
            } else if (productType === "Book") {
                productDetailsDiv.innerHTML = `
                    <label for="weight">Weight (Kg)</label>
                    <input type="text" id="weight" name="weight" required step="0.01">
                `;
                notificationDiv.innerText = "Please provide weight";
            } else if (productType === "Furniture") {
                productDetailsDiv.innerHTML = `
                <div class="furniture-height">
                    <label for="height">Height (cm)</label>
                    <input type="text" id="height" name="height" required>
                    </div>
                    <div class="furniture-width">
                    <label for="width">Width (cm)</label>
                    <input type="text" id="width" name="width" required>
                    </div>
                    <div class="furniture-length">
                    <label for="length">Length (cm)</label>
                    <input type="text" id="length" name="length" required>
                    </div>
                `;
                notificationDiv.innerText = "Please provide dimensions";
            }
        }

        function validateForm() {
    var sku = document.getElementById("sku").value.trim();
    var name = document.getElementById("name").value.trim();
    var price = document.getElementById("price").value.trim();
    var productType = document.getElementById("productType").value;
    var size = document.getElementById("size");
    var weight = document.getElementById("weight");
    var height = document.getElementById("height");
    var width = document.getElementById("width");
    var length = document.getElementById("length");
    var productDetailsDiv = document.getElementById("productDetails");

    var priceNumber = Number(price);
    if (price === "" || isNaN(priceNumber) || priceNumber <= 0) {
        showNotification("Please, provide a valid price greater than 0");
        return false;
    }

    if (sku === "" || name === "" || productType === "") {
        showNotification("Please submit required data");
        return false;
    }

    if (productType === "DVD") {
        if (size === null || size.value.trim() === "") {
            showNotification("Please provide size");
            return false;
        }
        var sizeNumber = Number(size.value);
        if (isNaN(sizeNumber) || sizeNumber <= 0) {
            showNotification("Please, provide a valid size greater than 0");
            return false;
        }
    }

    if (productType === "Book") {
        if (weight === null || weight.value.trim() === "") {
            showNotification("Please provide weight");
            return false;
        }
        var weightNumber = Number(weight.value);
        if (isNaN(weightNumber) || weightNumber <= 0) {
            showNotification("Please, provide a valid weight greater than 0");
            return false;
        }
    }

    if (productType === "Furniture") {
        if (height === null || height.value.trim() === "" ||
            width === null || width.value.trim() === "" ||
            length === null || length.value.trim() === "") {
            showNotification("Please provide dimensions");
            return false;
        }
        var heightNumber = Number(height.value);
        var widthNumber = Number(width.value);
        var lengthNumber = Number(length.value);
        if (isNaN(heightNumber) || heightNumber <= 0 ||
            isNaN(widthNumber) || widthNumber <= 0 ||
            isNaN(lengthNumber) || lengthNumber <= 0) {
            showNotification("Please, provide valid dimensions greater than 0");
            return false;
        }
    }

    if (!productDetailsDiv) {
        showNotification("Please, select a product type");
        return false;
    }

    return true;
}

function showNotification(message) {
    var notificationDiv = document.getElementById("notification");
    notificationDiv.innerText = message;
}

        function cancelAddProduct() {
            window.location.href = "list_products.php";
        }

        function checkAvailability(event) {
            event.preventDefault();
            const sku = document.getElementById("sku").value;
            const saveBtn = document.getElementById("save")
            // Create an XMLHttpRequest object
            const xhr = new XMLHttpRequest();

            // Configure the request
            xhr.open("POST", "check_sku.php", true);

            // Set the content type (if sending data)
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            // Define what happens when the response is ready
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        const response = xhr.responseText; // The response from PHP
                        document.getElementById("resultMessage").textContent = response;

                        // Enable the submit button if SKU is available
                        if (response === "SKU is available!") {
                            saveBtn.disabled = false;
                        } else {
                            saveBtn.disabled = true;
                        }
                    } else {
                        console.error("Error fetching data");
                    }
                }
            };

            // Send the request 
            xhr.send("sku=" + encodeURIComponent(sku));
        }
    </script>
</body>

</html>