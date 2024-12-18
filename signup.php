<?php
session_start(); // Start the session to access session variables
require_once('dbconnect.php'); // Include the database connection

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate user input
    if (empty($fullname) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Please fill in all fields.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if the email already exists in the database
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $error = "Email already exists. Please choose another.";
        } else {
            // Hash the password before storing it
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert the new user into the database
            $stmt = $pdo->prepare("INSERT INTO users (fullname, email, password) VALUES (:fullname, :email, :password)");
            $stmt->bindParam(':fullname', $fullname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);
            
            if ($stmt->execute()) {
                // Store user details in session for redirect after success
                $_SESSION['signup_success'] = true;
                header('Location: signup.php'); // Redirect to the same page to show success message
                exit;
            } else {
                $error = "There was an error during registration. Please try again.";
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

    <!-- Sign Up Form -->
    <div class="form-container">
        <h2>Create Your Account</h2>

        <?php if (isset($error)): ?>
            <p class="w3-text-red"><?php echo $error; ?></p>
        <?php elseif (isset($_SESSION['signup_success']) && $_SESSION['signup_success']): ?>
            <p class="w3-text-green">You have successfully signed up! Redirecting to the login page...</p>
            <script>
                // Redirect after 3 seconds
                setTimeout(function() {
                    window.location.href = "login.php"; // Redirect to login page
                }, 3000);
            </script>
        <?php endif; ?>

        <form method="POST">
            <input type="text" id="fullname" name="fullname" placeholder="Full Name" required>
            <input type="email" id="email" name="email" placeholder="Email Address" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Sign Up</button>
        </form>

        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Kimio Gadgets Store. All Rights Reserved.</p>
    </footer>

</body>
</html>

<?php
// Clear the session variable after showing the success message
if (isset($_SESSION['signup_success'])) {
    unset($_SESSION['signup_success']);
}
?>
