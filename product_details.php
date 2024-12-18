<?php
session_start(); // Start the session to access session variables

// Check if the user is logged in
if (!isset($_SESSION['fullname'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Include the database connection file
include 'dbconnect.php'; // Assuming dbconnect.php is in the same directory

// Check if the 'id' parameter is in the URL
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Prepare and execute the query to fetch the product details
    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = :id");
    $stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch the product details
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        // If the product doesn't exist, redirect to the product list page
        header("Location: products.php");
        exit();
    }
} else {
    // If no product ID is provided, redirect to the product list page
    header("Location: products.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details - Kimio Gadgets Store</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <style>
        .product-box {
            border: 1px solid #ddd;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            background-color: #fff;
        }
        .product-image {
            max-width: 100%;
            max-height: 400px;
            object-fit: contain;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .product-price {
            font-size: 1.5em;
            font-weight: bold;
            color: #007bff;
        }
        .product-description {
            margin-top: 15px;
            font-size: 1.1em;
            color: #555;
        }
        .product-details-container {
            max-width: 900px;
            margin: 0 auto;
        }
        .action-buttons {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .back-button {
            margin-top: 20px;
            text-align: center;
        }
        .edit-popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
            max-width: 500px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            z-index: 1000;
        }
        .edit-popup input,
        .edit-popup textarea,
        .edit-popup select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <div class="w3-bar w3-light-gray w3-card-2">
        <a href="index.php" class="w3-bar-item w3-button w3-large w3-text-dark">Kimio Gadgets Store</a>
        <div class="w3-right">
            <a href="index.php" class="w3-bar-item w3-button">Home</a>
            <a href="about.php" class="w3-bar-item w3-button">About Us</a>
            <a href="contact.php" class="w3-bar-item w3-button">Contact</a>
            <a href="logout.php" class="w3-bar-item w3-button"><?php echo $_SESSION['fullname']; ?></a>
        </div>
    </div>

    <!-- Product Details Section -->
    <div class="w3-container w3-padding-64 product-details-container">
        <div class="product-box">
            <h3 class="w3-center"><?php echo htmlspecialchars($product['product_name']); ?></h3>
            <div class="w3-row-padding w3-center">
                <div class="w3-half">
                    <img src="uploads/<?php echo isset($product['image']) ? $product['image'] : 'default.jpg'; ?>" alt="Product Image" class="product-image">
                </div>
                <div class="w3-half">
                    <p class="product-price">RM <?php echo number_format($product['price'], 2); ?></p>
                    <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                    <p><strong>Type:</strong> <?php echo htmlspecialchars($product['type']); ?></p>
                    <p><strong>Quantity Available:</strong> <?php echo htmlspecialchars($product['quantity']); ?></p>
                </div>
            </div>
            <div class="action-buttons">
                <button class="w3-button w3-green" onclick="showEditPopup()">Edit</button>
                <a href="delete_product.php?id=<?php echo $product_id; ?>" class="w3-button w3-red" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
            </div>
            <!-- Back to Products Button -->
            <div class="back-button">
                <a href="products.php" class="w3-button w3-large w3-blue">Back to Products</a>
            </div>
        </div>
    </div>

    <!-- Edit Product Popup -->
    <div class="overlay" id="overlay"></div>
    <div class="edit-popup" id="editPopup">
        <form action="edit_product.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
            <label for="product_name">Product Name:</label>
            <input type="text" name="product_name" id="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" required>

            <label for="image">Product Image:</label>
            <input type="file" name="image" id="image">

            <label for="price">Price:</label>
            <input type="number" step="0.01" name="price" id="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>

            <label for="description">Description:</label>
            <textarea name="description" id="description" rows="5" required><?php echo htmlspecialchars($product['description']); ?></textarea>

            <label for="type">Type:</label>
            <input type="text" name="type" id="type" value="<?php echo htmlspecialchars($product['type']); ?>" required>

            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" id="quantity" value="<?php echo htmlspecialchars($product['quantity']); ?>" required>

            <button type="submit" class="w3-button w3-blue">Save Changes</button>
            <button type="button" class="w3-button w3-gray" onclick="closeEditPopup()">Cancel</button>
        </form>
    </div>

    <script>
        function showEditPopup() {
            document.getElementById('overlay').style.display = 'block';
            document.getElementById('editPopup').style.display = 'block';
        }

        function closeEditPopup() {
            document.getElementById('overlay').style.display = 'none';
            document.getElementById('editPopup').style.display = 'none';
        }
    </script>

    <!-- Footer -->
    <footer class="w3-center w3-padding-32 w3-light-gray">
        <p>&copy; 2024 Kimio Gadgets Store. All Rights Reserved.</p>
    </footer>

</body>
</html>
