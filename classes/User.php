<?php
// classes/User.php
class User
{
    private $conn;
    private $table = 'users';

    public $id;
    public $username;
    public $email;
    public $user_image;
    public $user_bio;
    public $password;
    public $role;
    public $created_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Register new user
    public function register()
    {


        // Insert query
        $query = "INSERT INTO " . $this->table . "(username,email,password) VALUES(:username, :email, :password)";

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Clean and bind data
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = password_hash($this->password, PASSWORD_BCRYPT); // Hash the password
        // $this->role = htmlspecialchars(strip_tags($this->role));

        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        // $stmt->bindParam(':role', $this->role);

        // Execute the query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Login user
    public function login($username, $password)
    {

        // Select query
        $query = "SELECT * FROM " . $this->table . " WHERE username = :username";

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Clean and bind data
        $username = htmlspecialchars(strip_tags($username));
        $stmt->bindParam(':username', $username);

        // Execute the query
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if user exists and password is correct
        if ($row && password_verify($password, $row['password'])) {
            $this->id = $row['id'];
            $this->username = $row['username'];
            $this->role = $row['role'];
            $this->created_at = $row['created_at'];
            return true;
        }

        return false;
    }

    public function userExists($username)
    {
        $query = "SELECT id FROM users WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function emailExists($email)
    {
        $query = "SELECT id FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
    // Get user by ID
    public function getUserById($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 0,1";

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Bind ID
        $stmt->bindParam(':id', $id);

        // Execute the query
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->id = $row['id'];
            $this->username = $row['username'];
            $this->password = $row['password'];
            $this->email = $row['email'];
            $this->user_image = $row['user_image'];
            $this->user_bio = $row['user_bio'];
            $this->role = $row['role'];
            $this->created_at = $row['created_at'];
            return true;
        }

        return false;
    }

    // Update user image
    public function updateUserImage($image)
    {
        // Check if the user uploaded a file
        if (empty($image)) {
            $_SESSION["profile_msg"] = "Please upload an image.";
            header("location: profile.php");
            exit;
            // return false;
        }

        // Check if the file is an image
        $ext = explode('/', $image['type'])[1];
        if (!in_array($ext, ['jpeg', 'png', 'gif', 'jpg'])) {
            $_SESSION["profile_msg"] = "Please upload a valid image.";
            header("location: profile.php");
            exit;
            // return false;
        }

        // Check if the file size is less than 2MB
        if ($image['size'] > 2000000) {
            $_SESSION["profile_msg"] = "Please upload an image less than 2MB.";
            header("location: profile.php");
            exit;
            // return false;
        }

        // Create folder to store the images if it doesn't exist
        if (!file_exists('images/profile_images')) {
            mkdir('images/profile_images', 0777, true);
        }

        $query = "UPDATE " . $this->table . " SET user_image = :image WHERE id = :id";

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Bind ID and image
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':image', $image['name']);

        // Execute the query
        if ($stmt->execute()) {
            move_uploaded_file($image['tmp_name'], 'images/profile_images/' . $image['name']);
            $_SESSION["profile_msg"] = "Image uploaded successfully.";
            header("location: profile.php");
            exit;
            // return true;
        } else {
            $_SESSION["profile_msg"] = "Something went wrong. Please try again.";
            header("location: profile.php");
            exit;
            // return false;
        }
    }


    public function updateUserInfo($username, $email, $bio, $password)
    {
        $username_err = $email_err = $bio_err = $password_err = '';

        // Validate username
        if (!empty($username) && $username != $this->username && $this->userExists($username)) {
            $username_err = "Username already exists.";
        } elseif (!empty($username) && strlen($username) < 6) {
            $username_err = "Username must be at least 6 characters long.";
        }

        // Validate email
        if (!empty($email) && $email != $this->email && $this->emailExists($email)) {
            $email_err = "Email is already registered.";
        } elseif (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_err = "Invalid email format.";
        }

        // Validate bio
        if (empty($bio)) {
            $bio_err = "Please enter a bio.";
        }

        // Validate password
        if (!empty($password) && strlen($password) < 8) {
            $password_err = "Password must be at least 8 characters long.";
        }

        if ($username_err || $email_err || $bio_err || $password_err) {
            $_SESSION["profile_msg"] = $username_err . ' ' . $email_err . ' ' . $bio_err . ' ' . $password_err;
            header("location: profile.php");
            exit;
        }
        if ($username == $this->username && $email == $this->email && $bio == $this->user_bio && $password == "") {
            $_SESSION["profile_msg"] = "No changes made.";
            header("location: profile.php");
            exit;
        }
        // Prepare query dynamically based on which fields need to be updated
        $query = "UPDATE " . $this->table . " SET ";
        $params = [];

        if (!empty($username)) {
            $query .= "username = :username, ";
            $params[':username'] = $username;
        }
        if (!empty($email)) {
            $query .= "email = :email, ";
            $params[':email'] = $email;
        }
        if (!empty($password)) {
            $query .= "password = :password, ";
            $params[':password'] = password_hash($password, PASSWORD_BCRYPT);
        }
        $query .= "user_bio = :bio "; // Bio is always updated
        $params[':bio'] = $bio;

        $query .= "WHERE id = :id";
        $params[':id'] = $this->id;

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Execute the query
        if ($stmt->execute($params)) {
            $_SESSION["profile_msg"] = "Info updated successfully.";
            $_SESSION["user_name"] = $username;
            header("location: profile.php");
            exit;
        } else {
            $_SESSION["profile_msg"] = "Something went wrong. Please try again.";
            header("location: profile.php");
            exit;
        }
    }

    public function getRecentProfileUpdate($user_id)
    {
        $query = "SELECT last_updated FROM users WHERE id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
