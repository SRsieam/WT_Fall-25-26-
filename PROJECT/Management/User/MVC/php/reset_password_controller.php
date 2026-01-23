<?php
session_start();
include "../db/db_connection.php"; 

// Security: Prevent direct access without OTP verification
if (!isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true) {
    header("Location: forgot_password_controller.php");
    exit();
}

$msg = "";

if (isset($_POST['update_pass_btn'])) {
    $new_p = $_POST['n_pass'];
    $conf_p = $_POST['c_pass'];
    $email = $_SESSION['reset_email'];

    // Validation: Match your existing password rules
    if (strlen($new_p) < 6 || !preg_match("/[@$!%*#?&]/", $new_p)) {
        $msg = "Password must be at least 6 characters and include a special symbol.";
    } else if ($new_p !== $conf_p) {
        $msg = "Passwords do not match.";
    } else {
        $hashed_password = password_hash($new_p, PASSWORD_DEFAULT);
        
        // Update the user's password in the database
        $sql = "UPDATE users SET password = '$hashed_password' WHERE email = '$email'";
        if (mysqli_query($conn, $sql)) {
            // Success: Clear sessions and redirect to login
            session_destroy(); 
            header("Location: login_controller.php?msg=Password updated successfully!");
            exit();
        } else {
            $msg = "Database Error: " . mysqli_error($conn);
        }
    }
}

include "../html/reset_password_view.php";
?>