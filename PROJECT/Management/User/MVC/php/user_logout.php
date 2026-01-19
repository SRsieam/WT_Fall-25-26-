<?php
session_start();
// Path adjusted to point to the database model in the MVC folder
include '../db/db_connection.php';

// Check if a user is logged in before destroying the session
if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    // Update the last_logout timestamp for the specific user
    $sql = "UPDATE users SET last_logout = NOW() WHERE id = $user_id";
    mysqli_query($conn, $sql);
}

// Clear all session variables
session_unset();

// Destroy the session entirely
session_destroy();

// Redirect to the login controller within the MVC structure
header("Location: login_controller.php");
exit();
?>