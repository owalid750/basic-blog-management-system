<?php
include './config/config.php';
include './classes/Database.php';
include './classes/User.php';
$pageTitle = 'Register';
include 'header.php'; // Include the header



$username = $email = $password = "";
$username_err = $email_err = $password_err = "";

if (isset($_SESSION['user_name'])) {
    header("location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $db = $database->connect();
    $user = new User($db);

    $username = trim($_POST["username"]);
    if (empty($username)) {
        $username_err = "Please enter a username.";
    } elseif (strlen($username) < 6) {
        $username_err = "Username must be at least 6 characters long.";
    } elseif ($user->userExists($username)) {
        $username_err = "Username already exists.";
    }

    $email = trim($_POST["email"]);
    if (empty($email)) {
        $email_err = "Please enter an email.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format.";
    } elseif ($user->emailExists($email)) {
        $email_err = "Email is already registered.";
    }

    $password = trim($_POST["password"]);
    if (empty($password)) {
        $password_err = "Please enter a password.";
    } elseif (strlen($password) < 8) {
        $password_err = "Password must be at least 8 characters long.";
    } elseif (!preg_match("/[A-Z]/", $password)) {
        $password_err = "Password must contain at least one uppercase letter.";
    } elseif (!preg_match("/[a-z]/", $password)) {
        $password_err = "Password must contain at least one lowercase letter.";
    } elseif (!preg_match("/[0-9]/", $password)) {
        $password_err = "Password must contain at least one number.";
    } elseif (!preg_match("/[\W_]/", $password)) {
        $password_err = "Password must contain at least one special character.";
    }

    if (empty($username_err) && empty($email_err) && empty($password_err)) {
        $user->username = $username;
        $user->email = $email;
        $user->password = password_hash($password, PASSWORD_BCRYPT); // Hash the password

        if ($user->register()) {
            echo "<p>Registration successful! You can now <a href='login.php'>login</a>.</p>";
        } else {
            echo "<p>Registration failed. Please try again.</p>";
        }
    }
}
?>

<div class="container">
    <?php if (isset($_SESSION['message'])): ?>
        <p style="color: red;"><?php echo $_SESSION['message'];
                                ?></p>
    <?php endif ?>
    <h2>Register</h2>
    <p>Please fill this form to create an account.</p>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($username); ?>">
            <span class="help-block"><?php echo $username_err; ?></span>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>">
            <span class="help-block"><?php echo $email_err; ?></span>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" value="<?php echo htmlspecialchars($password); ?>">
            <span class="help-block"><?php echo $password_err; ?></span>
            <small class="form-text text-muted">
                <ul>
                    <li>Password must be at least 8 characters long.</li>
                    <li>Include at least one uppercase letter.</li>
                    <li>Include at least one lowercase letter.</li>
                    <li>Include at least one number.</li>
                    <li>Include at least one special character (e.g., @, #, $, etc.).</li>
                </ul>
            </small>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Register">
        </div>
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
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