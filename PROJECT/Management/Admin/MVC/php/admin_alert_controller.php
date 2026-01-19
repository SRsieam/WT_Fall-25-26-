<?php
session_start();
include '../db/db.php';

// Check Admin Login
if (!isset($_SESSION['is_admin_logged_in'])) {
    header("Location: admin_login_controller.php");
    exit();
}

$msg = "";
$msg_type = "";

// --- SEND ALERT LOGIC ---
if (isset($_POST['send_alert'])) {
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $type = mysqli_real_escape_string($conn, $_POST['alert_type']); 

    // 1. Deactivate existing alerts in 'alert' table
    mysqli_query($conn, "UPDATE alert SET is_active = 0");

    // 2. Insert new alert into 'alert' table
    $sql = "INSERT INTO alert (message, type, is_active) VALUES ('$message', '$type', 1)";
    
    if (mysqli_query($conn, $sql)) {
        $msg = "Broadcast sent successfully! Users will see it immediately.";
        $msg_type = "success-msg";
    } else {
        $msg = "Error sending alert: " . mysqli_error($conn);
        $msg_type = "error-msg"; 
    }
}

// --- STOP ALERT LOGIC ---
if (isset($_POST['stop_alert'])) {
    // Deactivate all alerts in 'alert' table
    $sql = "UPDATE alert SET is_active = 0";
    if (mysqli_query($conn, $sql)) {
        $msg = "All alerts have been stopped.";
        $msg_type = "success-msg";
    }
}

// Load the View
include '../html/admin_alert_view.php';
?>