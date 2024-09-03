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
<style>
    /* General Header Styles */
    .site-header {
        background-color: #333;
        color: #fff;
        padding: 20px 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
    }

    .site-header .logo {
        font-size: 24px;
        font-weight: bold;
        color: #fff;
        text-decoration: none;
    }

    /* Navbar Styles */
    .navbar {
        display: flex;
    }

    .navbar-nav {
        list-style-type: none;
        margin: 0;
        padding: 0;
        display: flex;
    }

    .navbar-nav li {
        margin-left: 20px;
    }

    .navbar-nav li a {
        color: #fff;
        text-decoration: none;
        font-weight: bold;
    }

    .navbar-nav li a:hover {
        color: #ffd700;
    }

    .navbar-text {
        color: #ffd700;
        font-weight: bold;
        margin-left: 20px;
    }



    /* Burger Menu Styles */
    .burger-menu {
        display: none;
        cursor: pointer;
    }

    .burger-menu div {
        width: 25px;
        height: 3px;
        background-color: #fff;
        margin: 5px 0;
    }

    /* Welcome Message Styles */
    .welcome-message-container {
        /* background-color: red; */
        color: #ffd700;
        text-align: center;
        /* padding-top: 10px; */
        /* padding: 12px 0; */
        font-size: 18px;
        font-weight: bold;
        /* box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); */
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
        .site-header {
            flex-direction: column;
            text-align: center;
        }

        .site-header .logo {
            font-size: 20px;
        }

        .navbar {
            display: none;
            /* Hide the navbar by default */
            flex-direction: column;
            width: 100%;
            text-align: center;
        }

        .navbar.active {
            display: flex;
            /* Show navbar when active class is added */
        }

        .navbar-nav {
            display: flex;
            flex-direction: column;
            width: 100%;
            padding: 0;
        }

        .navbar-nav li {
            margin: 10px 0;
        }

        .navbar-text {
            margin-left: 0;
            margin-top: 10px;
        }

        .burger-menu {
            display: block;
            /* Show the burger icon */
        }
    }
</style>

<body>

    <header class="site-header">
        <div class="container">

            <?php if ($isLoggedIn): ?>
                <div class="welcome-message-container">Welcome, <?php echo $_SESSION['user_name']; ?>!</div>
            <?php endif; ?>
            <a href="index.php" class="logo">My Blog</a>
            <div class="burger-menu" onclick="toggleMenu()">
                <div></div>
                <div></div>
                <div></div>
            </div>
            <nav class="navbar hidden">
                <ul class="navbar-nav">
                    <li><a href="index.php">Home</a></li>
                    <?php if ($isLoggedIn): ?>
                        <li><a href="profile.php">Profile</a></li>
                        <li><a href="manage.php">Manage</a></li>
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




    <script>
        function toggleMenu() {
            var navbar = document.querySelector('.navbar');
            navbar.classList.toggle('active');
            navbar.classList.toggle('hidden');
        }
    </script>