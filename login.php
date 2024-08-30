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
if (isset($_SESSION['user_name'])) {
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
    <?php if (isset($_SESSION['message'])): ?>
        <p style="color: red;"><?php echo $_SESSION['message'];
                                ?></p>
    <?php endif ?>
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

    <!-- Google Sign-in Button -->
    <div id="g_id_onload"
        data-client_id="870341719889-ropq99kbb41q6qf4tbbp1dug8gelvu1p.apps.googleusercontent.com"
        data-context="signin"
        data-ux_mode="popup"
        data-callback="loginCallback"
        data-auto_prompt="false">
    </div>

    <div class="g_id_signin"
        data-type="standard"
        data-shape="pill"
        data-theme="outline"
        data-text="continue_with"
        data-size="medium"
        data-logo_alignment="left">
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jwt-decode@3.1.2/build/jwt-decode.min.js"></script>

    <script>
        function loginCallback(response) {
            const token = jwt_decode(response.credential);

            // Extract necessary information from the token
            const googleId = token.sub;
            const username = token.name;
            const email = token.email;
            console.log(token);
            // Send a POST request to add_google_user.php
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "add_google_user.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // window.location.href = xhr.responseText;
                    // Reload the current page
                    window.location.reload();
                }
            };
            xhr.send("google_id=" + encodeURIComponent(googleId) +
                "&username=" + encodeURIComponent(username) +
                "&email=" + encodeURIComponent(email));
        }
    </script>
</div>