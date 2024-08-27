<?php
session_start();
require("./config/config.php");
require("./classes/Database.php");
require("./classes/Comment.php");

$connection = new Database();
$db = $connection->connect();
$comment = new Comment($db);
$action = isset($_GET["action"]) ? $_GET['action'] : "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if ($action == "add") {
        $post_id = htmlspecialchars(trim($_POST['post_id']));
        $user_id = htmlspecialchars(trim($_SESSION["user_id"]));
        $content = htmlspecialchars(trim($_POST['comment_content']));

        if (empty($content)) {
            $_SESSION["error_msg"] = "Comment Must Not be Empty !";
            header("location: post_details.php?id=" . $post_id);
            exit;
        } else {
            $comment->post_id = $post_id;
            $comment->user_id = $user_id;
            $comment->content = $content;
            if ($comment->createComment()) {
                $_SESSION["success_msg"] = "Comment Created Successfully :)";
                header("location: post_details.php?id=" . $post_id);
                exit;
            }
        }
    } elseif ($action == "edit") {
        $comment_id = htmlspecialchars(trim($_POST['id']));
        $content = htmlspecialchars(trim($_POST['content']));
        $post_id = htmlspecialchars(trim($_POST['post_id']));
        if (empty($content)) {
            $_SESSION["error_msg"] = "Comment Must Not be Empty !";
            header("location: post_details.php?id=" . $post_id);
            exit;
        } else {
            if ($comment->updateComment($comment_id, $content)) {
                $_SESSION["success_msg"] = "Comment Updated Successfully :)";
                header("location: post_details.php?id=" . $post_id);
                exit;
            }
        }
    }elseif ($action=="delete") {
        $comment_id = htmlspecialchars(trim($_POST['id']));
        $post_id = htmlspecialchars(trim($_POST['post_id']));
        if ($comment->deleteComment($comment_id)) {
            $_SESSION["success_msg"] = "Comment Deleted Successfully :)";
            header("location: post_details.php?id=" . $post_id);
            exit;
        }
    }
}
