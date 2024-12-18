<?php
session_start(); // Start the session to access session variables

// If the user is not logged in, redirect to the login page
if (!isset($_SESSION['fullname'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kimio Gadgets Store</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: "Roboto", sans-serif;
        }

        .hero-section {
            background: linear-gradient(to right, rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('hero.jpg') no-repeat center center/cover;
            color: white;
            text-align: center;
            height: 80vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .hero-section h1 {
            font-size: 3em;
            margin: 0;
            font-weight: bold;
        }

        .hero-section p {
            font-size: 1.2em;
            margin: 20px 0;
        }

        footer {
            background-color: #333;
            color: white;
            padding: 32px 16px;
        }

        footer a {
            color: #f9f9f9;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <div class="w3-bar w3-light-gray w3-card-2">
        <a href="index.php" class="w3-bar-item w3-button w3-large w3-text-dark"><strong>Kimio Gadgets Store</strong></a>
        <div class="w3-right">
            <a href="index.php" class="w3-bar-item w3-button">Home</a>
            <a href="products.php" class="w3-bar-item w3-button">Products</a>
            <a href="about.php" class="w3-bar-item w3-button">About Us</a>
            <a href="contact.php" class="w3-bar-item w3-button">Contact</a>
            <?php if (isset($_SESSION['fullname'])): ?>
                <a href="logout.php" class="w3-bar-item w3-button"><i class="fa fa-user"></i> <?php echo $_SESSION['fullname']; ?></a>
            <?php else: ?>
                <a href="login.php" class="w3-bar-item w3-button">Login</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="hero-section">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['fullname']); ?>!</h1>
        <p>We're glad to have you at Kimio Gadgets Store.</p>
        <a href="products.php" class="w3-button w3-green w3-large w3-round w3-padding-large">Add Product</a>
    </div>

    <!-- About Us Section -->
    <div class="w3-container w3-padding-64 w3-light-grey">
        <h3 class="w3-center">About Us</h3>
        <p class="w3-center w3-large">Kimio Gadgets Store is dedicated to bringing you the latest technology and gadgets at affordable prices. Explore our wide range of products, from smartphones to smart wearables.</p>
    </div>

    <!-- Footer -->
    <footer class="w3-center">
        <p>&copy; 2024 Kimio Gadgets Store. All Rights Reserved.</p>
        <p>Follow us on:
            <a href="#"><i class="fa fa-facebook"></i></a>
            <a href="#"><i class="fa fa-twitter"></i></a>
            <a href="#"><i class="fa fa-instagram"></i></a>
        </p>
    </footer>

</body>
</html>
