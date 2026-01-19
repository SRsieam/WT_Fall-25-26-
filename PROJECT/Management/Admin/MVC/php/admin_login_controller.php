<?php
session_start();
include '../db/db.php'; // Connect to Model

$error = "";

if (isset($_POST['login_btn'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    // Check credentials in admin table
    $sql = "SELECT * FROM admin WHERE email='$email' AND password='$password'"; 
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {

        $row = mysqli_fetch_assoc($result);
        $_SESSION['is_admin_logged_in'] = true;
        $_SESSION['admin_email'] = $row['email'];

        // Redirect to Dashboard Controller
        header("Location: admin_dashboard_controller.php");
        exit();

    } else {

        $error = "Invalid Email or Password";

    }
}

// Load the View
include '../html/admin_login_view.php';
?>