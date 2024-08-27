<?php
// Include the necessary files
include './config/config.php';
include './classes/Database.php';
include './classes/User.php';
$pageTitle = 'Register';
include 'header.php'; // Include the header

// Initialize variables for form inputs and error messages
$username = $email = $password = "";
$username_err = $email_err = $password_err = "";
if (isset($_SESSION['user_name'])){
    header("location: index.php");
    exit;
}
// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize database and user objects
    $database = new Database();
    $db = $database->connect();
    $user = new User($db);


    // Validate username
    $username = trim($_POST["username"]);
    if (empty($username)) {
        $username_err = "Please enter a username.";
    } elseif (strlen($username) < 6) {
        $username_err = "Username must be at least 6 characters long.";
    } elseif ($user->userExists($username)) { // Make sure userExists checks the username
        $username_err = "Username already exists.";
    }

    // Validate email
    $email = trim($_POST["email"]);
    if (empty($email)) {
        $email_err = "Please enter an email.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format.";
    } elseif ($user->emailExists($email)) { // Add emailExists method to check if email is already registered
        $email_err = "Email is already registered.";
    }

    // Validate password
    $password = trim($_POST["password"]);
    if (empty($password)) {
        $password_err = "Please enter a password.";
    } elseif (strlen($password) < 8) { // Ensure password meets minimum length
        $password_err = "Password must be at least 8 characters long.";
    } elseif (!preg_match("/[A-Z]/", $password)) { // Ensure password contains at least one uppercase letter
        $password_err = "Password must contain at least one uppercase letter.";
    } elseif (!preg_match("/[a-z]/", $password)) { // Ensure password contains at least one lowercase letter
        $password_err = "Password must contain at least one lowercase letter.";
    } elseif (!preg_match("/[0-9]/", $password)) { // Ensure password contains at least one number
        $password_err = "Password must contain at least one number.";
    } elseif (!preg_match("/[\W_]/", $password)) { // Ensure password contains at least one special character
        $password_err = "Password must contain at least one special character.";
    }

    // Check for errors before inserting into database
    if (empty($username_err) && empty($email_err) && empty($password_err)) {
        $user->username = $username;
        $user->email = $email;
        $user->password = $password;

        if ($user->register()) {
            echo "<p>Registration successful! You can now <a style='text-decoration: none;' href='login.php'>login</a>.</p>";
        } else {
            echo "<p>Registration failed. Please try again.</p>";
        }
    }
}



?>

<div class="container">
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
        <p>Already have an account? <a style="text-decoration: none;" href="login.php">Login here</a>.</p>
    </form>
</div>

