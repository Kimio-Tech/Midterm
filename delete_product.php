<?php
session_start(); // Start the session to access session variables

// Check if the user is logged in
if (!isset($_SESSION['fullname'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Include the database connection file
include 'dbconnect.php';

// Check if the 'id' parameter is in the URL
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    try {
        // Prepare the query to delete the product
        $stmt = $pdo->prepare("DELETE FROM products WHERE product_id = :product_id");
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);

        // Execute the query
        if ($stmt->execute()) {
            // Redirect to the products page with a success message
            $_SESSION['success_message'] = "Product deleted successfully.";
        } else {
            // Redirect to the products page with an error message
            $_SESSION['error_message'] = "Failed to delete the product.";
        }
    } catch (PDOException $e) {
        // Handle any errors during the deletion process
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
    }
} else {
    // Redirect to the products page with an error message if no ID is provided
    $_SESSION['error_message'] = "Invalid product ID.";
}

// Redirect back to the products page
header("Location: products.php");
exit();
?>
