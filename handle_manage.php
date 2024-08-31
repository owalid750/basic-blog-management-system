<?php
session_start();
include 'config/config.php';
include 'classes/Database.php';
include 'classes/User.php';
$database = new Database();
$db = $database->connect();
$user = new User($db);

$action = isset($_GET['action']) ? $_GET['action'] : "";

if ($action == 'edit') {
    $user_id = htmlspecialchars(trim($_POST['user_id']));
    $currentUserRole = $user->getUserRole($_POST['user_id'])['role'];
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $role = htmlspecialchars(trim($_POST['role'])) == null ? $currentUserRole : htmlspecialchars(trim($_POST['role']));
    $status = htmlspecialchars(trim($_POST['status']));

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION["error_msg"] = "Invalid email format.";
        header("location: manage.php");
        exit;
    }

    if ($user->updateUser($user_id, $username, $email, $role, $status)) {
        $_SESSION["success_msg"] = "User updated successfully.";
        header("location: manage.php");
        exit;
    }
} elseif ($action == "delete") {
    $user_id = htmlspecialchars(trim($_GET['id']));
    if ($user->deleteUser($user_id)) {
        $_SESSION["success_msg"] = "User deleted successfully.";
        header("location: manage.php");
        exit;
    }
}
// test
// echo $action;
