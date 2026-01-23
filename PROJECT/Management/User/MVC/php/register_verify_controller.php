<?php
session_start();
include "../db/db_connection.php";

$msg = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['verify_reg'])) {
    $user_code = trim($_POST['user_code']);
    
    if (isset($_SESSION['reg_otp']) && $user_code == $_SESSION['reg_otp']) {
        
        $data = $_SESSION['reg_data'];
        $name = mysqli_real_escape_string($conn, $data['name']);
        $email = mysqli_real_escape_string($conn, $data['email']);
        $division = $data['division'];
        $district = $data['district'];
        $dob = $data['dob'];
        $phone = $data['phone'];
        $password = password_hash($data['password'], PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (name, email, division, district, dob, phone, password) 
                VALUES ('$name', '$email', '$division', '$district', '$dob', '$phone', '$password')";

        if (mysqli_query($conn, $sql)) {
            unset($_SESSION['reg_otp']);
            unset($_SESSION['reg_data']);
            
            // Redirect to login with a success trigger
            header("Location: login_controller.php?success=1");
            exit();
        } else {
            $msg = "Database Error: " . mysqli_error($conn);
        }
    } else {
        $msg = "Incorrect verification code. Please try again.";
    }
}
include "../html/register_verify_view.php";
?>