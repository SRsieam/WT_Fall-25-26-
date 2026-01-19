<?php
session_start();

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to the Admin Login Controller
header("Location: admin_login_controller.php");
exit();
?>