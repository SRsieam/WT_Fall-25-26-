<?php
session_start();
include "../db/db_connection.php"; // Pointing to the Model

// 1. Authentication Check
$user_id = $_SESSION["user_id"] ?? 0;
$user_name = $_SESSION["user_name"] ?? "";

if ($user_id == 0) { 
    header("Location: user_login_controller.php"); 
    exit(); 
}

$success = $error = "";
$mode = $_GET['mode'] ?? 'view';

// 2. Handle DELETE Action
if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    $sql = "DELETE FROM posts WHERE id=$id AND user_id=$user_id";
    if (mysqli_query($conn, $sql)) {
        header("Location: user_profile_controller.php?mode=delete&success=deleted");
        exit();
    }
}

// 3. Handle UPDATE Action
if (isset($_POST['update'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $sql = "UPDATE posts SET content='$content' WHERE id=$id AND user_id=$user_id";
    if (mysqli_query($conn, $sql)) {
        $success = "Report updated successfully!";
    } else {
        $error = mysqli_error($conn);
    }
}

// 4. Fetch Post for Editing
$editPost = null;
if (isset($_GET['edit'])) {
    $id = mysqli_real_escape_string($conn, $_GET['edit']);
    $res = mysqli_query($conn, "SELECT * FROM posts WHERE id=$id AND user_id=$user_id");
    $editPost = mysqli_fetch_assoc($res);
}

// 5. Fetch Account Data & Statistics
$user_q = mysqli_query($conn, "SELECT * FROM users WHERE id = '$user_id'");
$user_data = mysqli_fetch_assoc($user_q);

$total_stats = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM posts WHERE user_id = '$user_id'"))['count'];
$pending_stats = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM posts WHERE user_id = '$user_id' AND status = 'Pending'"))['count'];
$resolved_stats = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM posts WHERE user_id = '$user_id' AND status = 'Resolved'"))['count'];

// 6. Fetch Reports List
$sql_list = "SELECT posts.*, categories.category_name 
             FROM posts 
             LEFT JOIN categories ON posts.category_id = categories.id 
             WHERE user_id = '$user_id' ORDER BY posts.id DESC";
$reports = mysqli_query($conn, $sql_list);

// Load the View
include "../html/user_profile_view.php";
?>