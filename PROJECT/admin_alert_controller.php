<?php
session_start();
include "../db/db_connection.php"; // Pointing to the Model

// Admin Authentication check
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

    // 1. Deactivate existing alerts
    mysqli_query($conn, "UPDATE alert SET is_active = 0");

    // 2. Insert new alert
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
    $sql = "UPDATE alert SET is_active = 0";
    if (mysqli_query($conn, $sql)) {
        $msg = "All alerts have been stopped.";
        $msg_type = "success-msg";
    }
}

// Load the View
include "../html/admin_alert_view.php";
?>