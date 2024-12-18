<?php
session_start(); // Start the session to access session variables

// Destroy the session to log the user out
session_unset(); // Remove all session variables
session_destroy(); // Destroy the session

// Redirect the user to the home page (index.html)
header("Location: index.html");
exit;
?>
