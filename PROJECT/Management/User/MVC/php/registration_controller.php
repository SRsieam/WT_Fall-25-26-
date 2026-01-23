<?php
session_start();
include "../db/db_connection.php"; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load local library files (No API used)
require '../libs/PHPMailer/src/Exception.php';
require '../libs/PHPMailer/src/PHPMailer.php';
require '../libs/PHPMailer/src/SMTP.php';

$msg = "";
$name = $email = $division = $district = $dob = $phone = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = mysqli_real_escape_string($conn, $_POST["name"]);
    $email    = mysqli_real_escape_string($conn, $_POST["email"]);
    $division = $_POST["division"];
    $district = $_POST["district"];
    $dob      = $_POST["dob"];
    $phone    = $_POST["phone"];
    $pass     = $_POST["password"];

    // 1. Generate 6-digit OTP
    $otp = rand(100000, 999999);
    $_SESSION['reg_otp'] = $otp;
    $_SESSION['reg_data'] = $_POST; // Store data to insert after verification

    // 2. Send Code via SMTP
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username   = 'crimealertaiub@gmail.com'; 
        $mail->Password   = 'ofhg zady ukvt brne'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('your_gmail@gmail.com', 'Crime Alert System');
        $mail->addAddress($email);
        $mail->Subject = 'Your Registration Code';
        $mail->Body    = "Your 6-digit verification code is: $otp";

        $mail->send();
        header("Location: ../html/register_verify_view.php"); // Redirect to enter code
        exit();
    } catch (Exception $e) {
        $msg = "Error sending email: " . $mail->ErrorInfo;
    }
}
include "../html/registration_view.php";
?>