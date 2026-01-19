<?php
session_start();
include 'db.php';

// Check if a user is logged in before destroying the session
if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    // ============== NEW CODE ADDED HERE ==============
    // Update the last_logout timestamp
    $sql = "UPDATE users SET last_logout = NOW() WHERE id = $user_id";
    mysqli_query($conn, $sql);
    // =================================================
}

// Now destroy the session
session_unset();
session_destroy();

// Redirect to login page or home page
header("Location: loginuser.php");
exit();
?>
