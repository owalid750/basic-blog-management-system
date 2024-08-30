<?php
include './config/config.php';
include './classes/Database.php';
include './classes/User.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['google_id'])) {
    $googleId = $_POST['google_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    $database = new Database();
    $db = $database->connect();

    $user = new User($db);

    // Check if the Google user already exists
    if ($user->googleUserExists($googleId)) {
        // User exists, log them in
        $userId = $user->getUserIdByGoogleId($googleId); // Method to get user ID by Google ID
        if ($userId) {
            $_SESSION['user_id'] = $userId['id'];
            $_SESSION['user_name'] = $userId['username'];
            $_SESSION['message'] = "success login";
            header("Location: index.php"); // Redirect to home page
            exit;
        } else {
            $_SESSION['message'] = "Login failed. Please try again.";
            header("Location: login.php");
            exit;
        }
    } else {

        // Register new Google user
        $randomChars = $user->generateRandomChars();
        $username_with_random_chars = "$username" . "$randomChars";
        $user->username = $username_with_random_chars;
        $user->email = $email;
        $user->google_id = $googleId;
        if ($user->userExists($username_with_random_chars)) {
            $_SESSION['message'] = "user name already exist";
            header("Location: login.php");
            exit;
        }
        // Register new Google user
        $result = $user->register(true); // Pass true for Google sign-in

        if ($result['success']) {
            // Set session for new user
            $_SESSION['user_id'] = $result['user_id'];
            $_SESSION['user_name'] = $result['user_name'];
            header("Location: index.php"); // Redirect to home page
            exit;
        } else {
            // Log the error message for debugging and display it
            error_log('Registration error: ' . $result['message']);
            $_SESSION['message'] = "Registration failed: " . $result['message'];
            header("Location: register.php");
            exit;
        }
    }
} else {
    $_SESSION['message'] = "Invalid request.";
    header("Location: register.php");
    exit;
}
