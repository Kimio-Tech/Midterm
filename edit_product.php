<?php
session_start(); // Start the session to access session variables

// Check if the user is logged in
if (!isset($_SESSION['fullname'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Include the database connection file
include 'dbconnect.php';

// Handle the form submission for editing a product
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['product_id'])) {
        $product_id = $_POST['product_id'];
        $product_name = $_POST['product_name'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $type = $_POST['type'];
        $quantity = $_POST['quantity'];

        // File upload handling
        $image = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];

        if (!empty($image)) {
            $upload_dir = 'uploads/';
            $upload_file = $upload_dir . basename($image);
            move_uploaded_file($image_tmp, $upload_file);

            // Update query with image
            $sql = "UPDATE products SET product_name = :product_name, price = :price, description = :description, type = :type, quantity = :quantity, image = :image WHERE product_id = :product_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':product_name' => $product_name,
                ':price' => $price,
                ':description' => $description,
                ':type' => $type,
                ':quantity' => $quantity,
                ':image' => $image,
                ':product_id' => $product_id,
            ]);
        } else {
            // Update query without image
            $sql = "UPDATE products SET product_name = :product_name, price = :price, description = :description, type = :type, quantity = :quantity WHERE product_id = :product_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':product_name' => $product_name,
                ':price' => $price,
                ':description' => $description,
                ':type' => $type,
                ':quantity' => $quantity,
                ':product_id' => $product_id,
            ]);
        }
        header("Location: products.php"); // Redirect to products page after update
        exit();
    }
}

// Handle delete action
if (isset($_GET['delete']) && isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Delete the product from the database
    $stmt = $pdo->prepare("DELETE FROM products WHERE product_id = :product_id");
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: products.php"); // Redirect to products page after deletion
    exit();
}

// Fetch product details for displaying
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = :id");
    $stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
    $stmt->execute();

    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        header("Location: products.php"); // Redirect if the product doesn't exist
        exit();
    }
} else {
    header("Location: products.php"); // Redirect if no product ID is provided
    exit();
}
?>
