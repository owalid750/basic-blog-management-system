<?php
$pageTitle = 'Home';
include 'header.php'; // Include the header
// Include the necessary files
require("./config/config.php");
require("./classes/Database.php");
require("./classes/User.php");
require("./classes/Category.php");
require("./classes/Post.php");
$currentUserId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';


// print_r($_SESSION);
// Initialize the Database and BlogPost class
$database = new Database();
$db = $database->connect();
//
$user = new User($db);
$category = new Category($db);
$post = new Post($db);
// Check if the user is active
if (!$user->isUserActive($currentUserId)) {
    // Unset all of the session variables
    $_SESSION = array();
    // Destroy the session
    session_destroy();
}
// Fetch recent blog posts
$category_id = isset($_GET['category']) ? $_GET['category'] : '';
if (isset($_GET['category'])) {
    $recentPosts = $post->getPosts(
        null,
        null,
        $category_id,
        null,
        null
    );
} else {
    $recentPosts = $post->getPosts();
}

$category_names = $category->getAllCategories();

// print_r($_SESSION);
?>
<style>
    /* Filter by Category Styles */
    .filter-container {
        margin-bottom: 20px;
    }

    .filter-container form {
        display: inline-block;
    }

    .filter-container select {
        padding: 5px;
        font-size: 16px;
    }
</style>
<div class="container">
    <section class="hero">
        <h1>Welcome to My Blog</h1>
        <p>Explore our latest posts, thoughts, and ideas.</p>
    </section>
    <?php if (isset($_SESSION['success_msg'])) {
        echo '<p style="color: green">' . $_SESSION['success_msg'] . '</p>';
        unset($_SESSION['success_msg']);
    } ?>
    <section class="recent-posts">
        <!-- Filter by Category -->
        <div class="filter-container">
            <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <label for="category">Filter by Category:</label>
                <select name="category" id="category" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    <?php foreach ($category_names as $category): ?>
                        <option value="<?php echo $category['id']; ?>" <?php echo isset($_GET['category']) && $_GET['category'] == $category['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>

        <!-- Create Post Button -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="post_create.php" style="text-decoration: none;" class="btn btn-primary">Create Post</a>
        <?php else: ?>
            <a href="login.php" style="text-decoration: none; color:black" class="btn btn-secondary">Login to create a post</a>
        <?php endif; ?>

        <!-- Recent Posts Section -->
        <h2>Recent Posts</h2>
        <?php if (!empty($recentPosts)): ?>
            <div class="posts-grid">
                <?php foreach ($recentPosts as $single_post): ?>
                    <article class="post-card">
                        <h3><a href="post_details.php?id=<?php echo $single_post['id']; ?>"><?php echo htmlspecialchars($single_post['title']); ?></a></h3>
                        <p>
                            <?php
                            $content = htmlspecialchars($single_post['content']);
                            echo substr($content, 0, 40) . '...';
                            ?>
                        </p>
                        <small>Posted on <?php echo $single_post['created_at']; ?> by <a style="text-decoration: none;" href="public_profile.php?id=<?php echo $single_post['user_id']; ?>"><?php echo $single_post['user_id'] == $currentUserId ? 'You' : htmlspecialchars($single_post['user_name']); ?></a></small>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No posts available.</p>
        <?php endif; ?>
    </section>

</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Animate Hero Section with subtle zoom and fade
        gsap.from(".hero", {
            opacity: 0,
            scale: 1.1,
            duration: 1.5,
            ease: "power2.out"
        });

        // Animate Success Message with a smooth fade-in
        gsap.from(".success-msg", {
            opacity: 0,
            y: 20,
            duration: 1,
            delay: 0.5,
            ease: "power1.out"
        });

        // Animate Filter Container with slide-up effect
        gsap.from(".filter-container", {
            opacity: 0,
            y: 30,
            duration: 1,
            delay: 1,
            ease: "power2.out"
        });

        // Animate Posts Grid with fade and scale
        gsap.from(".posts-grid", {
            opacity: 0,
            scale: 0.98,
            duration: 1,
            ease: "power2.out"
        });

        // Animate Post Cards with a subtle pop effect
        // gsap.from(".post-card", {
        //     opacity: 0,
        //     y: 20,
        //     scale: 0.98,
        //     duration: 0.8,
        //     stagger: 0.2,
        //     ease: "power2.out"
        // });

        // Button Hover Effect with smooth scale and shadow
        gsap.to(".btn", {
            scale: 1.05,
            duration: 0.3,
            ease: "power2.out",
            paused: true,
            repeat: 1,
            yoyo: true
        });

        // Add a shadow on hover for a more polished look
        gsap.to(".btn", {
            boxShadow: "0 4px 8px rgba(0, 0, 0, 0.2)",
            duration: 0.3,
            ease: "power2.out",
            paused: true,
            repeat: 1,
            yoyo: true
        });
    });
</script>



<?php include 'footer.php'; // Include the footer 
?>