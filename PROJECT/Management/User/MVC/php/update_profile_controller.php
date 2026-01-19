<?php
session_start();
include "../db/db_connection.php"; // Path to the Model

$user_id = $_SESSION["user_id"] ?? 0;
if ($user_id == 0) { 
    header("Location: login_controller.php"); 
    exit(); 
}

// Logic for AJAX Update Profile Information
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajax_update'])) {
    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $phone    = mysqli_real_escape_string($conn, $_POST['phone']);
    $division = mysqli_real_escape_string($conn, $_POST['division']);
    $district = mysqli_real_escape_string($conn, $_POST['district']);

    $update_sql = "UPDATE users SET name='$name', phone='$phone', division='$division', district='$district' WHERE id='$user_id'";
    
    if (mysqli_query($conn, $update_sql)) {
        $_SESSION["user_name"] = $name; 
        echo json_encode(["status" => "success", "message" => "Profile updated successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database error."]);
    }
    exit();
}

// Logic for AJAX Password Change
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajax_password'])) {
    $old_pass = $_POST['old_password'];
    $new_pass = $_POST['new_password'];

    $user_q = mysqli_query($conn, "SELECT password FROM users WHERE id = '$user_id'");
    $user_data = mysqli_fetch_assoc($user_q);

    if (password_verify($old_pass, $user_data['password'])) {
        $hashed_new = password_hash($new_pass, PASSWORD_DEFAULT);
        $update_pass_sql = "UPDATE users SET password='$hashed_new' WHERE id='$user_id'";
        
        if (mysqli_query($conn, $update_pass_sql)) {
            echo json_encode(["status" => "success", "message" => "Password changed successfully!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error updating password."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Current password is incorrect."]);
    }
    exit();
}

// Initial data fetch to populate the view
$query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$user_id'");
$user = mysqli_fetch_assoc($query);

// Load the View
include "../html/update_profile_view.php";
?>