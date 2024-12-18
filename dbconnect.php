<?php
// Database configuration
$host = 'localhost';  // Database host
$dbname = 'kimiogadgets';  // Database name
$username = 'root';  // Database username (default for XAMPP)
$password = '';  // Database password (default for XAMPP is empty)

// Set DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";

// Create a PDO instance
try {
    $pdo = new PDO($dsn, $username, $password); // Changed $conn to $pdo
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Optionally: Set default fetch mode
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    // Uncomment below to confirm the connection (optional)
    // echo "Connected successfully";
} catch (PDOException $e) {
    // Handle connection errors
    die("Connection failed: " . $e->getMessage());
}
?>
