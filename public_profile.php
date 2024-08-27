<?php
// Include the necessary files
require("./config/config.php");
require("./classes/Database.php");
require("./classes/User.php");
require("./classes/Post.php");
$pageTitle = 'Public Profile';
include 'header.php';

// Initialize the Database and BlogPost class
$database = new Database();
$db = $database->connect();
$user = new User($db);
$post = new Post($db);
$id = isset($_GET['id']) ? $_GET['id'] : '';
$user_info = $user->getUserById($id); // this will return true or false
// Define the path to the default image
$defaultImage = 'avatar_image.avif';

// Determine the user's image or use the default image if not set
$userImage = !empty($user->user_image) ? htmlspecialchars("./images/profile_images/" . $user->user_image) : $defaultImage;
$myPosts = $post->getPosts(null, null, null, null, $id);

?>

<style>
    /* Container and General Styles */
    .container-profile {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        box-sizing: border-box;
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

    /* Main Content Area */
    .main-content-profile {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .posts-profile {
        flex: 1;
        min-width: 300px;
    }

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

    /* Responsive Design */
    @media (max-width: 768px) {
        .header-profile {
            flex-direction: column;
            align-items: center;
        }

        .profile-info-profile {
            margin-left: 0;
            text-align: center;
            margin-top: 15px;
        }

        .main-content-profile {
            flex-direction: column;
        }

        .posts-profile {
            width: 100%;
        }
    }
</style>

<div class="container-profile">
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
    </header>
    <!-- Main Content Area -->
    <main class="main-content-profile">
        <section class="posts-profile">
            <h2>Posts</h2>
            <div class="post-list-profile">
                <?php if (!empty($myPosts)): ?>
                    <?php foreach ($myPosts as $single_post): ?>
                        <a href="post_details.php?id=<?php echo htmlspecialchars($single_post['id']); ?>" class="post-link-profile">
                            <article class="post-profile">
                                <h3><?php echo htmlspecialchars($single_post['title']); ?></h3>
                                <p><?php echo htmlspecialchars(substr($single_post['content'], 0, 100)) . "..."; ?></p>
                            </article>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No posts available</p>
                <?php endif; ?>
            </div>
        </section>
    </main>
</div>