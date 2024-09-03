<?php
$pageTitle = 'Post Details';
include 'header.php';
// Include the necessary files
require("./config/config.php");
require("./classes/Database.php");
require("./classes/User.php");
require("./classes/Category.php");
require("./classes/Post.php");
require("./classes/Comment.php");

$currentUserId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

// Initialize the Database and BlogPost class
$database = new Database();
$db = $database->connect();
$post = new Post($db);
$category = new Category($db);
$comment = new Comment($db);
$user = new User($db);
$post_id = isset($_GET['id']) ? $_GET['id'] : '';
$post_details = $post->getPosts(null, null, null, $post_id);
$comments = $comment->getAllComments($post_id);
$currentUserRole = isset($user->getUserRole($currentUserId)['role']) ? $user->getUserRole($currentUserId)['role'] : "";

//test 
// print_r($post);
// print_r($_SESSION);
// echo $currentUserRole;
?>

<style>
    /* Container for the post details */
    .post-details {
        max-width: 100%;
        margin: 30px auto;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 10px;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Styling for the post image */
    .post-details img {
        width: 100%;
        height: auto;
        max-height: 400px;
        object-fit: cover;
        border-radius: 8px;
    }

    /* Styling for the post content */
    .post-details .post-content {
        padding: 20px;
    }

    .post-details .post-content h2 {
        margin-top: 0;
        font-size: 2em;
        color: #333;
    }

    .post-details .post-content p {
        line-height: 1.6;
        /* Space between lines */
        color: #555;
        /* Text color */
        margin: 15px 0;
        /* Space around paragraphs */
        word-wrap: break-word;
        /* Handle long words or URLs */
        overflow-wrap: break-word;
        /* Handle long text */
        text-align: justify;
        /* Align text evenly to both left and right */
        max-width: 100%;
        /* Ensure content doesn't overflow */
        padding: 0 10px;
        /* Optional padding inside the container */
        box-sizing: border-box;
        /* Ensure padding is included in width calculation */
    }


    .post-details .post-content small {
        display: block;
        margin: 10px 0;
        color: #888;
    }

    /* Styling for the controls section */
    .post-details .post-content .controls {
        margin-top: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .post-details .post-content .controls a {
        text-decoration: none;
        color: #007bff;
        font-weight: bold;
        margin-right: 15px;
    }

    .post-details .post-content .controls a:hover {
        color: #0056b3;
    }

    .post-details .post-content .controls .btn {
        background-color: #333333;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
    }

    .post-details .post-content .controls .btn:hover {
        background-color: #555555;
        color: white;
    }

    /* Styling for default image placeholder */
    .default-image {
        background-color: #333333;
        color: #fff;
        padding: 50px;
        text-align: center;
        border-radius: 8px;
        font-size: 1.5em;
        margin: 20px 0;
    }

    /*  */
    /* Container and General Styling */
    /* .container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
        font-family: Arial, sans-serif;
        color: #333;
    } */

    /* Success Message */
    .success-msg {
        color: #28a745;
        background-color: #dff0d8;
        border: 1px solid #d4edda;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    /* error_msg */
    .error_msg {
        color: #dc3545;
        /* Red text color */
        background-color: #f8d7da;
        /* Light red background */
        border: 1px solid #f5c6cb;
        /* Slightly darker red border */
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    /* Comment Form */
    .comment-form {
        margin-top: 30px;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #ffffff;
    }

    .comment-form h3 {
        margin-bottom: 15px;
        color: #333333
    }

    .comment-form textarea {
        width: 100%;
        /* padding: 10px; */
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-bottom: 10px;
        font-size: 14px;
    }

    .comment-form .btn {
        background-color: #333333;
        color: #fff;
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .comment-form .btn:hover {
        background-color: #555555;
    }

    /* Comments Section */
    /* General Section Styling */
    .comments-section {
        background-color: #f8f9fa;
        /* Light gray background */
        padding: 20px;
        border-radius: 8px;
        margin-top: 40px;
    }

    .comments-section h3 {
        font-size: 24px;
        margin-bottom: 20px;
        color: #343a40;
        /* Dark gray color */
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 10px;
    }

    /* Comments List */
    .comments-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .single-comment {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 8px;
        background-color: #fff;
        /* White background for comments */
        border: 1px solid #e9ecef;
    }

    .comment-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .comment-author {
        font-weight: bold;
        color: #007bff;
        /* Bootstrap primary color */
        font-size: 16px;
    }

    .comment-date {
        font-size: 14px;
        color: #6c757d;
        /* Bootstrap muted text color */
    }

    .comment-content {
        font-size: 15px;
        line-height: 1.6;
        color: #495057;
        /* Slightly dark gray */
        margin-bottom: 10px;
        overflow-wrap: break-word;
        word-wrap: break-word;
        overflow: hidden;
    }

    /* Comment Actions */
    .comment-actions {
        display: flex;
        gap: 10px;
    }

    .comment-actions .btn {
        font-size: 14px;
        padding: 5px 10px;
        border-radius: 5px;
        text-decoration: none;
        color: #fff;
    }

    .comment-actions .btn-edit {
        background-color: #333333;
        /* Info color */
    }

    .comment-actions .btn-delete {
        background-color: #333333;
        /* Danger color */
    }

    .comment-actions .btn:hover {
        opacity: 0.85;
    }

    /* No Comments Notice */
    .no-comments {
        font-size: 16px;
        color: #6c757d;
        /* Muted color */
        text-align: center;
        padding: 20px;
        border-radius: 8px;
        background-color: #fff;
        border: 1px solid #e9ecef;
        margin-top: 20px;
    }

    /* Login Prompt */
    .login-prompt {
        margin-top: 20px;
        font-size: 14px;
    }

    .login-prompt a {
        color: #007bff;
        text-decoration: none;
    }

    .login-prompt a:hover {
        text-decoration: underline;
    }

    /* Dialog Overlay */
    .dialog {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        /* Black with opacity for the overlay */
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        /* High z-index to ensure it's on top */
    }

    /* Dialog Content */
    .dialog-content {
        background-color: #fff;
        padding: 20px;

        border-radius: 10px;
        max-width: 500px;
        width: 100%;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        /* Slight shadow for depth */
        position: relative;
    }

    /* Close Button */
    .close-btn {
        position: absolute;
        top: 15px;
        right: 15px;
        font-size: 24px;
        color: #333;
        cursor: pointer;
        border: none;
        background: none;
    }

    .close-btn:hover {
        color: #333333;
        /* Change color on hover */
    }

    /* Form Elements */
    .dialog-content h2 {
        margin-bottom: 15px;
        font-size: 24px;
        color: #343a40;
        /* Dark gray */
    }

    .dialog-content textarea {
        width: calc(100% - 50px);
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ced4da;
        /* Light gray border */
        margin-bottom: 15px;
        font-size: 16px;
        resize: vertical;
        /* Allow vertical resizing only */
    }

    .dialog-content .btn {
        background-color: #333333;
        /* Bootstrap primary color */
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }

    .dialog-content .btn:hover {
        background-color: #515151;
        /* Darker blue on hover */
    }
</style>




<div class="container">
    <?php if (isset($_SESSION['success_msg'])): ?>
        <p class="success-msg"><?php echo $_SESSION['success_msg'];
                                unset($_SESSION['success_msg']); ?></p>
    <?php endif; ?>
    <?php if (isset($_SESSION['error_msg'])): ?>
        <p class="error_msg"><?php echo $_SESSION['error_msg'];
                                unset($_SESSION['error_msg']); ?></p>
    <?php endif; ?>
    <div class="post-details">
        <?php if (!empty($post_details)): ?>
            <?php foreach ($post_details as $single_post): ?>
                <h1><?php echo htmlspecialchars($single_post['title']); ?></h1>
                <h3>Category: <?php echo htmlspecialchars($single_post['category_name']); ?></h3>
                <?php if (empty($single_post['post_image'])): ?>
                    <div class="default-image">
                        <?php echo htmlspecialchars($single_post['title']); ?>
                    </div>
                <?php else: ?>
                    <img src="images/<?php echo htmlspecialchars($single_post['post_image']); ?>" alt="<?php echo htmlspecialchars($single_post['title']); ?>">
                <?php endif; ?>
                <div class="post-content">
                    <p><?php echo htmlspecialchars($single_post['content']); ?></p>
                    <small>Posted on <?php echo htmlspecialchars($single_post['created_at']); ?> by <a style="text-decoration: none;" href="public_profile.php?id=<?php echo htmlspecialchars($single_post['user_id']); ?>"><?php echo $single_post['user_id'] == $currentUserId ? 'You' : htmlspecialchars($single_post['user_name']);  ?></a></small>
                    <div class="controls">
                        <?php if ($single_post['user_id'] == $currentUserId || $currentUserRole == 'sysAdmin'): ?>
                            <a href="post_edit.php?id=<?php echo htmlspecialchars($single_post['id']); ?>" class="btn">Edit</a>
                            <form action="post_delete.php" method="post" onsubmit="return confirm('Are you sure you want to delete this post this will delete all the comments associated with it?')">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($single_post['id']); ?>">
                                <button type="submit" class="btn">Delete</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            <!-- Comment Form -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="comment-form">
                    <h3>Leave a Comment</h3>
                    <form action="comment_handler.php?action=add" method="post">
                        <textarea name="comment_content" rows="4" required placeholder="Write your comment here..."></textarea>
                        <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($single_post['id']); ?>">

                        <button type="submit" class="btn">Submit</button>
                    </form>
                </div>
            <?php else: ?>
                <p class="login-prompt">You must be logged in to leave a comment. <a style="text-decoration: none" href="login.php">Login here</a></p>
            <?php endif; ?>

            <!-- Comments Section -->
            <div class="comments-section">
                <h3>Comments</h3>
                <?php if (!empty($comments)): ?>
                    <ul class="comments-list">
                        <?php foreach ($comments as $comment): ?>
                            <li class="single-comment">
                                <div class="comment-header">
                                    <span class="comment-author"><?php echo $currentUserId == $comment['user_id'] ? 'You' : htmlspecialchars($comment['user_name']); ?></span>
                                    <span class="comment-date"><?php echo htmlspecialchars($comment['created_at']); ?></span>
                                </div>
                                <p class="comment-content"><?php echo htmlspecialchars($comment['content']); ?></p>
                                <?php if ($currentUserId == $comment['user_id'] || $currentUserId == $post_details[0]['user_id'] || $currentUserRole == 'sysAdmin'): ?>
                                    <div class="comment-actions">
                                        <button type="button" class="btn btn-edit" onclick="toggleDialog('update-comment-dialog-<?php echo htmlspecialchars($comment['id']); ?>')">Edit</button>
                                        <div id="update-comment-dialog-<?php echo htmlspecialchars($comment['id']); ?>" class="dialog" style="display: none;">
                                            <div class="dialog-content">
                                                <span class="close-btn" onclick="toggleDialog('update-comment-dialog-<?php echo htmlspecialchars($comment['id']); ?>')">&times;</span>
                                                <h2>Update Comment</h2>
                                                <form action="comment_handler.php?action=edit" method="post">
                                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($comment['id']); ?>">
                                                    <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($comment['post_id']); ?>">
                                                    <textarea name="content" rows="4" required><?php echo htmlspecialchars($comment['content']); ?></textarea>
                                                    <button type="submit" class="btn">Update</button>
                                                </form>
                                            </div>
                                        </div>
                                        <form action="comment_handler.php?action=delete" method="post" onsubmit="return confirm('Are you sure you want to delete this comment?')">
                                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($comment['id']); ?>">
                                            <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($comment['post_id']); ?>">
                                            <button type="submit" class="btn btn-delete">Delete</button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="no-comments">No comments yet. Be the first to comment!</p>
                <?php endif; ?>
            </div>

        <?php else: ?>
            <p>Post Not Found</p>
        <?php endif; ?>



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