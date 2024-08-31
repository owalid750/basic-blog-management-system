<?php
// classes/User.php
class User
{
    private $conn;
    private $table = 'users';

    public $id;
    public $google_id;
    public $username;
    public $email;
    public $user_image;
    public $user_bio;
    public $password;
    public $role;
    public $created_at;
    public $user_status;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Register new user
    /* public function register()
    {


        // Insert query
        $query = "INSERT INTO " . $this->table . "(username,email,password) VALUES(:username, :email, :password)";

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Clean and bind data
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        // $this->password = password_hash($this->password, PASSWORD_BCRYPT); // Hash the password
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
    } */
    // Register new user
    /* public function register($isGoogleUser = false)
    {
        // Adjust the query based on whether the user signed up with Google or not
        if ($isGoogleUser) {
            // Insert query for Google users (without a password)
            $query = "INSERT INTO " . $this->table . " (username, email, google_id) VALUES(:username, :email, :google_id)";
        } else {
            // Insert query for traditional users (with a password)
            $query = "INSERT INTO " . $this->table . "(username, email, password) VALUES(:username, :email, :password)";
        }

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Clean and bind data
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));

        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);

        if ($isGoogleUser) {
            // Bind Google ID for Google users
            $this->google_id = htmlspecialchars(strip_tags($this->google_id));
            $stmt->bindParam(':google_id', $this->google_id);
        } else {
            // Hash the password for traditional users and bind it
            // $this->password = password_hash($this->password, PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $this->password);
        }

        // Execute the query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    } */
    /*  public function register($isGoogleUser = false)
    {
        if ($isGoogleUser) {
            $query = "INSERT INTO " . $this->table . " (username, email, google_id) VALUES (:username, :email, :google_id)";
        } else {
            $query = "INSERT INTO " . $this->table . " (username, email, password) VALUES (:username, :email, :password)";
        }

        $stmt = $this->conn->prepare($query);

        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));

        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);

        if ($isGoogleUser) {
            $this->google_id = htmlspecialchars(strip_tags($this->google_id));
            $stmt->bindParam(':google_id', $this->google_id);
        } else {
            $stmt->bindParam(':password', $this->password);
        }

        if ($stmt->execute()) {
            return $this->conn->lastInsertId(); // Return the last inserted user ID
        }

        return false;
    } */
    /*   public function register($isGoogleUser = false)
    {
        try {
            if ($isGoogleUser) {
                $query = "INSERT INTO " . $this->table . " (username, email, google_id) VALUES (:username, :email, :google_id)";
            } else {
                $query = "INSERT INTO " . $this->table . " (username, email, password) VALUES (:username, :email, :password)";
            }

            $stmt = $this->conn->prepare($query);

            $this->username = htmlspecialchars(strip_tags($this->username));
            $this->email = htmlspecialchars(strip_tags($this->email));

            $stmt->bindParam(':username', $this->username);
            $stmt->bindParam(':email', $this->email);

            if ($isGoogleUser) {
                $this->google_id = htmlspecialchars(strip_tags($this->google_id));
                $stmt->bindParam(':google_id', $this->google_id);
            } else {
                $stmt->bindParam(':password', $this->password);
            }

            if ($stmt->execute()) {
                $userId = $this->conn->lastInsertId(); // Get the last inserted user ID
                return ['success' => true, 'user_id' => $userId];
            }

            return false;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    } */
    public function register($isGoogleUser = false)
    {
        try {
            if ($isGoogleUser) {
                $query = "INSERT INTO " . $this->table . " (username, email, google_id) VALUES (:username, :email, :google_id)";
            } else {
                $query = "INSERT INTO " . $this->table . " (username, email, password) VALUES (:username, :email, :password)";
            }

            $stmt = $this->conn->prepare($query);

            $this->username = htmlspecialchars(strip_tags($this->username));
            $this->email = htmlspecialchars(strip_tags($this->email));

            $stmt->bindParam(':username', $this->username);
            $stmt->bindParam(':email', $this->email);

            if ($isGoogleUser) {
                $this->google_id = htmlspecialchars(strip_tags($this->google_id));
                $stmt->bindParam(':google_id', $this->google_id);
            } else {
                $stmt->bindParam(':password', $this->password);
            }

            if ($stmt->execute()) {
                $userId = $this->conn->lastInsertId(); // Get the last inserted user ID

                // Fetch the username for the inserted user
                $query = "SELECT username FROM " . $this->table . " WHERE id = :id LIMIT 1";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':id', $userId);
                $stmt->execute();

                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $username = $row['username'];

                return [
                    'success' => true,
                    'user_id' => $userId,
                    'user_name' => $username,
                    'message' => 'User registered successfully.'
                ];
            } else {
                // If execute fails, return a detailed error message
                $errorInfo = $stmt->errorInfo();
                return ['success' => false, 'message' => 'Failed to execute statement: ' . $errorInfo[2]];
            }
        } catch (PDOException $e) {
            // Log the exception message and return it for debugging
            error_log($e->getMessage());
            return ['success' => false, 'message' => 'PDOException: ' . $e->getMessage()];
        }
    }



    // Method to check if a Google user already exists
    public function googleUserExists($googleId)
    {
        // Query to check if a user with the given Google ID exists
        $query = "SELECT id FROM " . $this->table . " WHERE google_id = :google_id LIMIT 1";

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Bind the Google ID parameter
        $stmt->bindParam(':google_id', $googleId);

        // Execute the query
        $stmt->execute();

        // Check if a row was returned
        if ($stmt->rowCount() > 0) {
            return true; // User with this Google ID already exists
        }

        return false; // User does not exist
    }
    // Method to get user ID by Google ID
    /* public function getUserIdByGoogleId($googleId)
    {
        $query = "SELECT id,username FROM " . $this->table . " WHERE google_id = :google_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':google_id', $googleId);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $row;
        }

        return false;
    } */
    public function getUserIdByGoogleId($googleId)
    {
        $query = "SELECT id, username FROM " . $this->table . " WHERE google_id = :google_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':google_id', $googleId);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $row;
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

    public function userExists($username, $current_user_id = null)
    {
        $query = "SELECT id FROM users WHERE username = :username";
        if ($current_user_id != null) {
            $query .= " AND id != :current_user_id";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        if ($current_user_id != null) {
            $stmt->bindParam(':current_user_id', $current_user_id);
        }
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function emailExists($email, $current_user_id = null)
    {
        $query = "SELECT id FROM users WHERE email = :email";
        if ($current_user_id != null) {
            $query .= " AND id != :current_user_id";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        if ($current_user_id != null) {
            $stmt->bindParam(':current_user_id', $current_user_id);
        }
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
            $this->user_status = $row['status'];
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

    //
    function generateRandomChars($length = 4)
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $randomString;
    }

    public function isUserActive($userId)
    {
        $query = "SELECT status FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Check if the status indicates the user is active
            return $row['status'] === 'active';
        }

        return false;
    }

    // MANAGE 
    public function getUserRole($user_id)
    {
        $query = "SELECT role FROM users WHERE id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getAllUsersExceptCurrent($currentUserId, $role)
    {
        if ($role === 'superAdmin') {
            $query = "SELECT * FROM users WHERE id != :currentUserId AND role != 'superAdmin' AND role != 'sysAdmin' ";
        } elseif ($role === 'admin') {
            $query = "SELECT * FROM users WHERE id != :currentUserId AND role = 'user'";
        } elseif ($role == "sysAdmin") {
            $query = "SELECT * FROM users WHERE id != :currentUserId ";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':currentUserId', $currentUserId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateUser($userId, $username, $email, $role = null, $status = null)
    {
        if (empty($username) || empty($email)) {
            $_SESSION["error_msg"] = "Username and email are required.";
            header("location: manage.php");
            exit;
        }
        if (strlen($username) < 4) {
            $_SESSION["error_msg"] = "User name at least 4 characters.";
            header("location: manage.php");
            exit;
        }
        if ($this->userExists($username, $userId)) {
            $_SESSION["error_msg"] = "Username already exists.";
            header("location: manage.php");
            exit;
        }


        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if ($this->emailExists($email, $userId)) {
                $_SESSION["error_msg"] = "Email already exists.";
                header("location: manage.php");
                exit;
            }
        }
        $query = "UPDATE users SET username = :username, email = :email";
        if ($role !== null) {
            $query .= ", role = :role";
        }
        if ($status !== null) {
            $query .= ", status = :status";
        }
        $query .= " WHERE id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        if ($role !== null) {
            $stmt->bindParam(':role', $role);
        }
        if ($status !== null) {
            $stmt->bindParam(':status', $status);
        }
        return $stmt->execute();
    }

    public function deleteUser($userId)
    {
        $query = "DELETE FROM users WHERE id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
