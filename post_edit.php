<?php
ob_start();
$pageTitle = 'Edit Post';
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
$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$post_details = $post->getPosts(null, null, null, $post_id);

if (!isset($_SESSION["user_id"])) {
    header("location:index.php");
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = htmlspecialchars(trim($_POST["title"]));
    $content = htmlspecialchars(trim($_POST["content"]));
    $category_id = htmlspecialchars(trim($_POST["category"]));
    $id = htmlspecialchars(trim($_POST["post_id"]));
    // Validate inputs
    if (empty($title)) {
        $_SESSION["error_msg"] = "Please enter title.";
        header('location: post_edit.php?id=' . $id);
        exit;
    } elseif (strlen($title) < 10) {
        $_SESSION["error_msg"] = "Title should be at least 10 characters.";
        header('location: post_edit.php?id=' . $id);
        exit;
    }

    if (empty($content)) {
        $_SESSION["error_msg"] = "Please enter content.";
        header('location: post_edit.php?id=' . $id);
        exit;
    } elseif (strlen($content) < 100) {
        $_SESSION["error_msg"] = "Content should be at least 100 characters.";
        header('location: post_edit.php?id=' . $id);
        exit;
    }

    if (empty($category_id)) {
        $$_SESSION["error_msg"] = "Please select category.";
        header('location: post_edit.php?id=' . $id);
        exit;
    }

    if (empty($_SESSION["error_msg"])) {
        if ($post->updatePost($id, $title, $content, $category_id)) {
            $_SESSION["success_msg"] = "Post updated successfully.";
            header('location: post_details.php?id=' . $id);
            exit;
        } else {
            $_SESSION["error_msg"] = "Something went wrong. Please try again later.";
            header('location: post_edit.php?id=' . $id);
            exit;
        }
    }
}
?>
<style>
    .alert {
        color: red;
    }
</style>
<div class="container">
    <h2 class="page-title">Edit Post</h2>
    <?php if (isset($_SESSION["error_msg"])): ?>
        <div class="alert alert-danger">
            <?php echo $_SESSION["error_msg"]; ?>
            <?php unset($_SESSION["error_msg"]); ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($post_details)): ?>
        <?php foreach ($post_details  as $single_post): ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="hidden" name="post_id" value="<?php echo $single_post['id']; ?>">
                <div class="form-group">
                    <label for="title">Title: </label>
                    <input type="text" name="title" id="title" class="form-control" value="<?php echo htmlspecialchars($single_post['title']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="content">Content: </label>
                    <textarea name="content" id="content" rows="8" class="form-control" required><?php echo htmlspecialchars($single_post['content']); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="category">Category: </label>
                    <select name="category" id="category" class="form-control" required>
                        <option value="">Select a category</option>
                        <?php foreach ($category_names as $category_name): ?>
                            <option value="<?php echo htmlspecialchars($category_name['id']); ?>" <?php echo ($category_name['id'] == $single_post['category_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category_name['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Post Not Found</p>
    <?php endif; ?>
</div>
<?php ob_end_flush();?>