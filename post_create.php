<?php
$pageTitle = 'Create Post';
include 'header.php';
// Include the necessary files
require("./config/config.php");
require("./classes/Database.php");
require("./classes/User.php");
require("./classes/Category.php");
require("./classes/Post.php");

// Initialize the Database and BlogPost class
$database = new Database();
$db = $database->connect();
$post = new Post($db);
$category = new Category($db);
$category_names = $category->getAllCategories();

if (!isset($_SESSION["user_id"])) {
    header("location:index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = htmlspecialchars(trim($_POST["title"]));
    $content = htmlspecialchars(trim($_POST["content"]));
    $category_id = htmlspecialchars(trim($_POST["category"]));
    $user_id = $_SESSION["user_id"];
    // Validate inputs
    if (empty($title)) {
        $title_err = "Please enter title.";
    } elseif (strlen($title) < 10) {
        $title_err = "Title should be at least 10 characters.";
    }

    if (empty($content)) {
        $content_err = "Please enter content.";
    } elseif (strlen($content) < 100) {
        $content_err = "Content should be at least 100 characters.";
    }

    if (empty($category_id)) {
        $category_err = "Please select category.";
    }

    if (empty($title_err) && empty($content_err) && empty($category_err)) {
        $post->title = $title;
        $post->content = $content;
        $post->category_id = $category_id;
        $post->user_id = $user_id;
        if ($post->createPost()) {
            $_SESSION["success_msg"] = "Post created successfully.";
            header("location:index.php");
            exit;
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}

//test
// print_r($category_names);
?>
<style>
    .alert {
        color: red;
        font-size: 14px;
        margin: 10px 0;

    }
</style>
<div class="container">
    <h2 class="page-title">Create Post</h2>
    <?php if (isset($title_err) || isset($content_err) || isset($category_err) || isset($error)): ?>
        <div class="alert alert-danger">
            <?php echo $title_err ?? ''; ?>
            <?php echo $content_err ?? ''; ?>
            <?php echo $category_err ?? ''; ?>
            <?php echo $error ?? ''; ?>
        </div>
    <?php endif; ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label for="title">Title: </label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="content">Content: </label>
            <textarea name="content" id="content" rows="8" class="form-control" required></textarea>
        </div>
        <div class="form-group">
            <label for="category">Category: </label>
            <select name="category" id="category" class="form-control" required>
                <option value="">Select a category</option>
                <?php foreach ($category_names as $category_name): ?>
                    <option value="<?php echo htmlspecialchars($category_name['id']); ?>"><?php echo htmlspecialchars($category_name['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <input type="submit" value="Create Post" class="btn btn-primary">
        </div>
    </form>
</div>