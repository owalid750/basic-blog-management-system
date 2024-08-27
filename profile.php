<?php
// Include the necessary files
require("./config/config.php");
require("./classes/Database.php");
require("./classes/User.php");
require("./classes/Post.php");
require("./classes/Comment.php");


$pageTitle = 'Profile';
include 'header.php'; // Include the header
if (!isset($_SESSION["user_id"])) {
    header("location:index.php");
    exit;
}

// Initialize the Database and BlogPost class
$database = new Database();
$db = $database->connect();
$user = new User($db);
$post = new Post($db);
$comment = new Comment($db);
$user_info = $user->getUserById($_SESSION['user_id']); // this will return true or false
// Define the path to the default image
$defaultImage = 'avatar_image.avif';

// Determine the user's image or use the default image if not set
$userImage = !empty($user->user_image) ? htmlspecialchars("./images/profile_images/" . $user->user_image) : $defaultImage;
$currentUserId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$myPosts = $post->getPosts(null, null, null, null, $currentUserId);
$totalPosts = $post->getTotalPosts($currentUserId);
$totalComments = $comment->getTotalComments($currentUserId);
$recentPost = $post->getRecentPost($currentUserId);
$recentComment = $comment->getRecentComment($currentUserId);
$recentProfileUpdate = $user->getRecentProfileUpdate($currentUserId);
// test
// var_dump($user->role);
// print_r($myPosts);
// echo count($myPosts);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_POST['form_type']) && $_POST['form_type'] == 'update_image') {
        // Update user image
        if (isset($_FILES['profile_picture'])) {
            $image = $_FILES['profile_picture'];
            $user->updateUserImage($image);
        }
    } elseif (isset($_POST['form_type']) && ($_POST['form_type'] === 'update_info')) {
        // update user info
        $user_name = htmlspecialchars(trim($_POST['username']));
        $email = htmlspecialchars(trim($_POST['email']));
        $password = htmlspecialchars(trim($_POST['password']));
        $user_bio = htmlspecialchars(trim($_POST['user_bio']));
        $user->updateUserInfo($user_name, $email, $user_bio, $password);
    }
}
// test
// print_r($_SESSION);
// Display error messages if set
$profile_msg = isset($_SESSION["profile_msg"]) ? htmlspecialchars($_SESSION["profile_msg"]) : '';
//test
// echo $user->password;
// echo "<br>";
// var_dump(password_verify("Esraa123@", $user->password));

?>

<!-- <link rel="stylesheet" href="./public/css/profile.css"> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    /* Container and General Styles */
    .container-profile {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    /* Header Section */
    .header-profile {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .profile-picture-profile img {
        border-radius: 50%;
        width: 150px;
        height: 150px;
        object-fit: cover;
    }

    .profile-info-profile {
        flex-grow: 1;
        margin-left: 20px;
    }

    .profile-info-profile h1 {
        margin: 0;
        font-size: 24px;
        color: #333;
    }

    .profile-info-profile p {
        margin: 5px 0;
        color: #666;
    }

    /* Button Container */
    .btn-container {
        display: flex;
        gap: 10px;
    }

    .btn-profile {
        background-color: #333333;
        color: white;
        border: none;
        padding: 10px 20px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-profile i {
        margin-right: 8px;
    }

    .btn-profile:hover {
        background-color: #555555;
    }

    /* Main Content Area */
    .main-content-profile {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .overview-profile,
    .posts-profile {
        flex: 1;
        min-width: 300px;
    }

    .overview-profile h2,
    .posts-profile h2 {
        color: #333;
    }

    .post-list-profile {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .post-profile {
        background-color: #f9f9f9;
        padding: 15px;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .post-profile h3 {
        margin: 0 0 10px 0;
        color: #333;
    }

    .post-profile p {
        margin: 0;
        color: #666;
    }

    /* Dialog Styles */
    .dialog-profile {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .dialog-content-profile {
        background-color: white;
        padding: 20px;
        border-radius: 5px;
        width: 100%;
        max-width: 500px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    .close-btn-profile {
        background-color: transparent;
        border: none;
        font-size: 24px;
        color: #333;
        cursor: pointer;
        float: right;
    }

    .form-group-profile {
        margin-bottom: 15px;
    }

    .form-group-profile label {
        display: block;
        margin-bottom: 5px;
        color: #333;
    }

    .form-group-profile input,
    .form-group-profile textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        box-sizing: border-box;
    }

    .save-btn-profile {
        background-color: #333333;
        color: white;
        border: none;
        padding: 10px 20px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .save-btn-profile i {
        margin-right: 8px;
    }

    .save-btn-profile:hover {
        background-color: #555555;
    }

    /* Post Link Styles */
    .post-link-profile {
        text-decoration: none;
        color: inherit;
        display: block;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .post-link-profile:hover .post-profile,
    .post-link-profile:focus .post-profile {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    /* Post Profile Animation */
    .post-profile {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background-color: #f9f9f9;
        padding: 15px;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .post-profile:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .recent-activity-link {
        color: #333333;
        text-decoration: none;
        transition: color 0.3s ease-in-out, transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        display: inline-block;
        /* Ensures that transform and box-shadow work properly */
    }

    .recent-activity-link:hover {
        color: #ffd700;
        transform: translateX(5px) scale(1.05) rotate(-2deg);
        background-color: #333333;
        /* Slightly scale up and rotate */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        /* Add a subtle shadow */
    }



    /* Responsive Design */
    @media (max-width: 768px) {
        .header-profile {
            flex-direction: column;
            align-items: flex-start;
        }

        .profile-info-profile {
            margin-left: 0;
            margin-top: 15px;
        }

        .btn-container {
            justify-content: flex-start;
        }

        .main-content-profile {
            flex-direction: column;
        }
    }
</style>


<div class="container-profile">
    <?php
    if ($profile_msg) {
        echo '<p style="color: red">' . $profile_msg . '</p>';
        unset($_SESSION["profile_msg"]); // Unset after displaying
    }
    ?>
    <!-- Header Section -->
    <header class="header-profile">
        <div class="profile-picture-profile">
            <img src="<?php echo htmlspecialchars($userImage); ?>" alt="profile picture">
        </div>
        <div class="profile-info-profile">
            <h1><?php echo htmlspecialchars($user->username); ?></h1>
            <p><?php echo htmlspecialchars($user->email); ?></p>
            <p>Bio: <?php echo htmlspecialchars($user->user_bio); ?></p>
        </div>
        <div class="btn-container">
            <button class="btn-profile update-info-btn-profile" onclick="toggleDialog('update-info-dialog')">
                <i class="fas fa-user-edit"></i> Update Info
            </button>
            <button class="btn-profile update-info-btn-profile" onclick="toggleDialog('update-image-dialog')">
                <i class="fas fa-image"></i> Update Image
            </button>
        </div>
    </header>


    <!-- Main Content Area -->
    <main class="main-content-profile">
        <section class="overview-profile">
            <h2>Overview</h2>
            <p>Total Posts: <?php echo $totalPosts; ?></p>
            <p>Total Comments: <?php echo $totalComments; ?></p>
            <p>Recent Activity:</p>
            <ul>
                <li>
                    <?php if ($recentPost): ?>
                        <a href="post_details.php?id=<?php echo htmlspecialchars($recentPost['id']); ?>" class="recent-activity-link">
                            Posted: "<?php echo htmlspecialchars($recentPost['title']); ?>"
                        </a>
                    <?php else: ?>
                        No recent posts.
                    <?php endif; ?>
                </li>
                <li>
                    <?php if ($recentComment): ?>
                        <a href="post_details.php?id=<?php echo htmlspecialchars($recentComment['post_id']); ?>" class="recent-activity-link">
                            Commented on: "<?php echo htmlspecialchars($recentComment['title']); ?>"
                        </a>
                    <?php else: ?>
                        No recent comments.
                    <?php endif; ?>
                </li>
                <li>
                    <?php if ($recentProfileUpdate): ?>
                        <a href="profile.php" class="recent-activity-link">
                            Updated Profile Picture on <?php echo htmlspecialchars($recentProfileUpdate['last_updated']); ?>
                        </a>
                    <?php else: ?>
                        No recent profile updates.
                    <?php endif; ?>
                </li>
            </ul>
        </section>



        <section class="posts-profile">
            <h2>Posts</h2>
            <div class="post-list-profile">
                <?php if (!empty($myPosts)): ?>
                    <?php foreach ($myPosts as $post): ?>
                        <a href="post_details.php?id=<?php echo htmlspecialchars($post['id']); ?>" class="post-link-profile">
                            <article class="post-profile">
                                <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                                <p><?php echo htmlspecialchars(substr($post['content'], 0, 100)) . "..."; ?></p>
                            </article>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No posts available <a style="text-decoration: none;" href="post_create.php">Create</a>.</p>
                <?php endif; ?>
            </div>
        </section>


    </main>
</div>

<!-- Update Info Dialog -->
<div id="update-info-dialog" class="dialog-profile" style="display: none;">
    <div class="dialog-content-profile">
        <span class="close-btn-profile" onclick="toggleDialog('update-info-dialog')">&times;</span>
        <h2>Update Info</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <input type="hidden" name="form_type" value="update_info">

            <div class="form-group-profile">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user->username); ?>" required>
            </div>
            <div class="form-group-profile">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user->email); ?>" required>
            </div>
            <div class="form-group-profile">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Leave Blank if you don't want to change your password.">
            </div>
            <div class="form-group-profile">
                <label for="bio">Bio:</label>
                <textarea id="bio" name="user_bio" rows="4"><?php echo htmlspecialchars($user->user_bio); ?></textarea>
            </div>
            <button type="submit" class="btn-profile save-btn-profile">
                <i class="fas fa-save"></i> Save Changes
            </button>
        </form>
    </div>
</div>

<!-- Update Image Dialog -->
<div id="update-image-dialog" class="dialog-profile" style="display: none;">
    <div class="dialog-content-profile">
        <span class="close-btn-profile" onclick="toggleDialog('update-image-dialog')">&times;</span>
        <h2>Update Profile Image</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="form_type" value="update_image">

            <div class="form-group-profile">
                <label for="profile-picture">Choose New Profile Picture:</label>
                <input type="file" id="profile-picture" name="profile_picture" required>
            </div>
            <button type="submit" class="btn-profile save-btn-profile">
                <i class="fas fa-upload"></i> Upload Image
            </button>
        </form>
    </div>
</div>

<script>
    function toggleDialog(dialogId) {
        var dialog = document.getElementById(dialogId);
        if (dialog.style.display === "none" || dialog.style.display === "") {
            dialog.style.display = "flex";
        } else {
            dialog.style.display = "none";
        }
    }
</script>