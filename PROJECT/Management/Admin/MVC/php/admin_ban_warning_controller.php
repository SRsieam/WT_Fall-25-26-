<?php
session_start();
include '../db/db.php';

// Check Admin Login
if (!isset($_SESSION['is_admin_logged_in'])) {
    header("Location: admin_login_controller.php");
    exit();
}

$msg = "";
$prefill_id = "";

// Check for ID passed via URL (e.g., from Users Details page)
if (isset($_GET['user_id'])) {
    $prefill_id = intval($_GET['user_id']);
}

// Handle Form Submission
if (isset($_POST['ban_user'])) {
    $user_id = intval($_POST['user_id']);
    $action_type = $_POST['action']; 
    
    // Determine the status string based on selection
    if ($action_type == 'ban') {
        $status = 'Banned';
    } elseif ($action_type == 'suspend') {
        $status = 'Suspended';
    } elseif ($action_type == 'unban') {
        $status = 'user'; // Reset to default role
    }
    
    // Update the user's role in the database
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

// Load the View
include '../html/admin_ban_warning_view.php';
?>