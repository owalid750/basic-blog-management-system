<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'My Blog'; ?></title>
    <link rel="stylesheet" href="/blog_management_system/public/css/styles.css"> <!-- Link to your CSS file -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.0/gsap.min.js"></script>
    <script src="https://accounts.google.com/gsi/client" async></script>
</head>

<body>
    <header class="site-header">
        <div class="container">
            <a href="index.php" class="logo">My Blog</a>
            <nav class="navbar">
                <ul class="navbar-nav">
                    <li><a href="index.php">Home</a></li>
                    <?php if ($isLoggedIn): ?>
                        <li><a href="profile.php">Profile</a></li>
                        <li><a href="manage.php">Manage</a></li>
                        <li class="navbar-text">Welcome, <?php echo $_SESSION['user_name']; ?>!</li>
                        <li>
                            <form action="logout.php" method="post" onsubmit="return confirm('Are you sure you want to log out?');">
                                <button type="submit">Logout</button>
                            </form>
                        </li>
                    <?php else: ?>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>