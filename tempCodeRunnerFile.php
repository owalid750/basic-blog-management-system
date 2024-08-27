<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $db = $database->connect();
    $post = new Post($db);
    $post_id = htmlspecialchars(trim($_POST["post_id"]));
    if ($post->deletePost($post_id)) {
        $_SESSION["success_msg"] = "Post deleted successfully.";
        header('location: index.php');
        exit;
    };
} else {
    header('location: index.php');
    exit;
}

print_r($_SESSION);