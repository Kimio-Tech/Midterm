<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['fullname'])) {
    header("Location: login.php");
    exit();
}

// Include database connection file
include 'dbconnect.php';

// Initialize variables
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = trim($_POST['product_name']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);
    $type = trim($_POST['type']);
    $quantity = trim($_POST['quantity']);
    $image = $_FILES['image']['name'];

    // Validate inputs
    if (empty($product_name) || empty($price) || empty($description) || empty($type) || empty($quantity)) {
        $error = "Please fill in all fields.";
    } elseif (!is_numeric($price) || $price <= 0) {
        $error = "Price must be a positive number.";
    } elseif (!is_numeric($quantity) || $quantity < 0) {
        $error = "Quantity must be a non-negative number.";
    } else {
        $upload_ok = 1;
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($image);
        $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Image validations
        if (!empty($image)) {
            $check = getimagesize($_FILES['image']['tmp_name']);
            if ($check === false) {
                $error = "Uploaded file is not an image.";
                $upload_ok = 0;
            }

            if ($_FILES['image']['size'] > 5000000) {
                $error = "Image size should not exceed 5MB.";
                $upload_ok = 0;
            }

            if (!in_array($image_file_type, ['jpg', 'png', 'jpeg', 'gif'])) {
                $error = "Allowed image formats: JPG, PNG, JPEG, GIF.";
                $upload_ok = 0;
            }

            if ($upload_ok && !move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $error = "Failed to upload image.";
            }
        }

        if (empty($error)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO products (product_name, price, description, type, quantity, image) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$product_name, $price, $description, $type, $quantity, $image]);
                $_SESSION['success_message'] = "Product added successfully!";
                header("Location: products.php");
                exit();
            } catch (PDOException $e) {
                $error = "Error adding product: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product | Kimio Gadgets Store</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h2 {
            font-size: 1.8rem;
            color: #333;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            font-weight: 500;
            margin-bottom: 5px;
        }
        input[type="text"], input[type="number"], input[type="file"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background: #45a049;
        }
        .back-btn {
            display: inline-block;
            margin-top: 15px;
            text-align: center;
            padding: 10px 20px;
            background: #2196F3;
            color: #fff;
            border-radius: 5px;
            text-decoration: none;
        }
        .back-btn:hover {
            background: #0b7dda;
        }
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            color: #fff;
        }
        .alert.error {
            background: #e74c3c;
        }
        .alert.success {
            background: #2ecc71;
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <div class="w3-bar w3-light-gray w3-card-2">
        <a href="index.php" class="w3-bar-item w3-button w3-large w3-text-dark">Kimio Gadgets Store</a>
        <div class="w3-right">
            <a href="products.php" class="w3-bar-item w3-button">Products</a>
            <a href="logout.php" class="w3-bar-item w3-button"><?php echo $_SESSION['fullname']; ?></a>
        </div>
    </div>

    <!-- Add Product Form -->
    <div class="w3-container w3-padding-32">
        <h2 class="w3-center">Add New Product</h2>

        <?php if (!empty($error)) : ?>
            <div class="w3-panel w3-red">
                <p><?php echo $error; ?></p>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)) : ?>
            <div class="w3-panel w3-green">
                <p><?php echo $success; ?></p>
            </div>
        <?php endif; ?>

        <div class="container">
            <form action="add_product.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="product_name">Product Name</label>
                    <input type="text" name="product_name" id="product_name" required>
                </div>

                <div class="form-group">
                    <label for="price">Price (RM)</label>
                    <input type="number" name="price" id="price" step="0.01" required>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" name="description" id="description" required>
                </div>

                <div class="form-group">
                    <label for="type">Type</label>
                    <input type="text" name="type" id="type" required>
                </div>

                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="number" name="quantity" id="quantity" required>
                </div>

                <div class="form-group">
                    <label for="image">Upload Image</label>
                    <input type="file" name="image" id="image">
                </div>

                <button type="submit">Add Product</button>
            </form>

            <a href="products.php" class="back-btn">Back to Products</a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="w3-center w3-padding-32 w3-light-gray">
        <p>&copy; 2024 Kimio Gadgets Store. All Rights Reserved.</p>
    </footer>

</body>
</html>
