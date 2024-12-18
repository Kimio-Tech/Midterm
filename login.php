<?php
session_start(); // Start the session to access session variables
require_once('dbconnect.php'); // Include the database connection

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate user input
    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        // Query the database for the user's email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch();

        // Check if the user exists and the password matches
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['fullname'] = $user['fullname']; // Store the fullname in session
            $_SESSION['email'] = $user['email']; // Store the email in session
            header('Location: index.php'); // 
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    }
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
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #333;
            padding: 10px 20px;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-size: 18px;
        }
        .navbar a:hover {
            text-decoration: underline;
        }
        .form-container {
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 80px auto;
        }
        .form-container h2 {
            font-size: 24px;
            margin-bottom: 30px;
            text-align: center;
            color: #333;
        }
        .form-container input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .form-container input:focus {
            border-color: #007BFF;
            outline: none;
        }
        .form-container button {
            width: 100%;
            padding: 12px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .form-container button:hover {
            background-color: #0056b3;
        }
        .form-container p {
            text-align: center;
            font-size: 16px;
        }
        .form-container p a {
            color: #007BFF;
            text-decoration: none;
        }
        .form-container p a:hover {
            text-decoration: underline;
        }
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 20px;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <div class="navbar">
        <a href="index.html">Kimio Gadgets Store</a>
        <a href="products.php">Products</a>
        <a href="about.php">About Us</a>
        <a href="contact.php">Contact</a>
        <a href="login.php">Login</a>
    </div>

    <!-- Login Form -->
    <div class="form-container">
        <h2>Login to Your Account</h2>

        <?php if (isset($error)): ?>
            <p class="w3-text-red"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            <button type="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Kimio Gadgets Store. All Rights Reserved.</p>
    </footer>

</body>
</html>
