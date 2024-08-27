<?php
// Include the necessary files
include './config/config.php';
include './classes/Database.php';
include './classes/User.php';
$pageTitle = 'Login';
include 'header.php'; // Include the header


// Initialize variables for form inputs and error messages
$username  = $password = "";
$username_err = $password_err = "";
if (isset($_SESSION['user_name'])){
    header("location: index.php");
    exit;
}
// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize database and user objects
    $database = new Database();
    $db = $database->connect();
    $user = new User($db);

    // Validate username
    $username = trim($_POST["username"]);
    if (empty($username)) {
        $username_err = "Please enter a username.";
    }

    // Validate password
    $password = trim($_POST["password"]);
    if (empty($password)) {
        $password_err = "Please enter a password.";
    }

    // Check credentials
    if (empty($username_err) && empty($password_err)) {
        if ($user->login($username, $password)) {
            // Redirect to a protected page (e.g., dashboard)
            $_SESSION["user_id"] = $user->id;
            $_SESSION["user_name"] = $user->username;
            header("location: index.php");
            exit;
        } else {
            $password_err = "Invalid username or password.";
        }
    }
}
?>
<div class="container">
    <h2>Login</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($username); ?>">
            <span class="help-block"><?php echo $username_err; ?></span>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" value="<?php echo htmlspecialchars($password); ?>">
            <span class="help-block"><?php echo $password_err; ?></span>

        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Login">
        </div>
        <p>Don't have an account? <a style="text-decoration: none;" href="register.php">Register here</a>.</p>
    </form>
</div>
