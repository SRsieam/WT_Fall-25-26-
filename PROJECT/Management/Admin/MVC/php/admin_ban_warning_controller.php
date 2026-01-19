<?php
session_start();
include '../db/db.php';

if (!isset($_SESSION['is_admin_logged_in'])) {
    header("Location: admin_login_controller.php");
    exit();
}

$msg = "";
$prefill_id = "";

if (isset($_GET['user_id'])) {
    $prefill_id = intval($_GET['user_id']);
}

if (isset($_POST['ban_user'])) {
    $user_id = intval($_POST['user_id']);
    $action_type = $_POST['action']; 
    
    if ($action_type == 'ban') {
        $status = 'Banned';
    } elseif ($action_type == 'suspend') {
        $status = 'Suspended';
    } elseif ($action_type == 'unban') {
        $status = 'user'; 
    }

    $sql = "UPDATE users SET role='$status' WHERE id=$user_id";
    
    if(mysqli_query($conn, $sql)) {
        if ($status == 'user') {
            $msg = "User #$user_id is now Active (Unbanned).";
        } else {
            $msg = "User #$user_id status updated to: $status";
        }
    } else {
        $msg = "Error updating user: " . mysqli_error($conn);
    }
}

include '../html/admin_ban_warning_view.php';
?>