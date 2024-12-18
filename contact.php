<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Kimio Gadgets Store</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <style>
        .contact-us-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 50px 20px;
        }
        .contact-us-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .contact-us-form {
            font-size: 1.1em;
            color: #555;
        }
        .contact-us-input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .contact-us-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
        }
        .contact-us-button:hover {
            background-color: #0056b3;
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
            <a href="login.php" class="w3-bar-item w3-button">Login</a> <!-- Optional: If you want to provide login option -->
        </div>
    </div>

    <!-- Contact Us Section -->
    <div class="w3-container contact-us-container">
        <div class="contact-us-header">
            <h2>Contact Us</h2>
        </div>
        <div class="contact-us-form">
            <p>If you have any questions or need assistance, please feel free to reach out to us. We are here to help you with any inquiries you may have about our products or services.</p>

            <form action="submit_contact.php" method="POST">
                <input type="text" class="contact-us-input" name="name" placeholder="Your Name" required>
                <input type="email" class="contact-us-input" name="email" placeholder="Your Email" required>
                <textarea class="contact-us-input" name="message" placeholder="Your Message" rows="5" required></textarea>
                <button type="submit" class="contact-us-button">Send Message</button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="w3-center w3-padding-32 w3-light-gray">
        <p>&copy; 2024 Kimio Gadgets Store. All Rights Reserved.</p>
    </footer>

</body>
</html>
