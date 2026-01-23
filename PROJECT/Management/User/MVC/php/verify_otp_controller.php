<?php
session_start();

// Security: If no OTP exists in session, kick them back
if (!isset($_SESSION['reset_otp'])) {
    header("Location: forgot_password_controller.php");
    exit();
}

$msg = "";

if (isset($_POST['verify_otp_btn'])) {
    $user_input = trim($_POST['otp_code']);

    // Check if input matches the secret session code
    if ($user_input == $_SESSION['reset_otp']) {
        $_SESSION['otp_verified'] = true; // Mark as verified
        header("Location: reset_password_controller.php"); 
        exit();
    } else {
        $msg = "Invalid code. Please check your Gmail again.";
    }
}

// Load the verification view
include "../html/verify_otp_view.php";
?>