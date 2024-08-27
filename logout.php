<?php
// Initialize the session
session_start();

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Unset all of the session variables
    $_SESSION = array();

    // Destroy the session.
    session_destroy();

    // Redirect to the login page or homepage
    header("location: index.php");
    exit;
} else {
    // Redirect to home or another page if accessed via GET
    header("location: index.php");
    exit;
}
