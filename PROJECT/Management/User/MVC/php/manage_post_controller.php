<?php
session_start();
// Include the database model from the MVC folder structure
include "../db/db_connection.php";

$user_id = $_SESSION["user_id"] ?? 0;

// Security Check: Only logged-in users can manage posts
if ($user_id == 0) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';
    // Secure input to prevent SQL injection
    $post_id = mysqli_real_escape_string($conn, $_POST['post_id']);

    // Authorization Check: Ensure the post belongs to the current user
    $check = mysqli_query($conn, "SELECT id FROM posts WHERE id='$post_id' AND user_id='$user_id'");
    if (mysqli_num_rows($check) == 0) {
        echo json_encode(["status" => "error", "message" => "Unauthorized access to this post."]);
        exit();
    }

    // Handle Delete Action
    if ($action == 'delete') {
        $query = "DELETE FROM posts WHERE id='$post_id'";
        if (mysqli_query($conn, $query)) 
        {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Delete failed."]);
        }
    } 
    // Handle Update Action
    elseif ($action == 'update') {
        $content = mysqli_real_escape_string($conn, $_POST['content']);
        $query = "UPDATE posts SET content='$content' WHERE id='$post_id'";
        if (mysqli_query($conn, $query)) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Update failed."]);
        }
    }
    exit();
}
?>