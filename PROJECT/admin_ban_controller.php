<?php
session_start();
include "../db/db_connection.php"; // Path to the Model

// Admin Authentication check
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
    
    // Get current admin ID to prevent self-logout bug
    $current_admin_id = $_SESSION['admin_id'] ?? null;

    if ($user_id === $current_admin_id) {
        $msg = "<span style='color:red;'>Error: You cannot modify your own administrative status.</span>";
    } else {
        // Determine the status string exactly as in raw code
        if ($action_type == 'ban') {
            $status = 'Banned';
        } elseif ($action_type == 'suspend') {
            $status = 'Suspended';
        } elseif ($action_type == 'unban') {
            $status = 'user'; 
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
}

// Load the View file
include "../html/admin_ban_view.php";
?>