<?php
session_start();
include "../db/db_connection.php"; //

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../libs/PHPMailer/src/Exception.php';
require '../libs/PHPMailer/src/PHPMailer.php';
require '../libs/PHPMailer/src/SMTP.php';

$msg = "";

if (isset($_POST['send_code'])) {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    
    // Verify if email exists in your users table
    $query = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
    
    if (mysqli_num_rows($query) > 0) {
        $otp = rand(100000, 999999); // Generate 6-digit code
        $_SESSION['reset_otp'] = $otp;
        $_SESSION['reset_email'] = $email;

        $mail = new PHPMailer(true);
        try {
            // SMTP Settings for Gmail
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'crimealertaiub@gmail.com'; 
            $mail->Password   = 'ofhg zady ukvt brne'; // 16-digit Google App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('crimealertaiub@gmail.com', 'Crime Alert System');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Verification Code';
            $mail->Body    = "Your 6-digit verification code is: <b>$otp</b>";

            $mail->send();
            header("Location: verify_otp_controller.php"); // Redirect to next step
            exit();
        } catch (Exception $e) {
            $msg = "Mail could not be sent. Error: {$mail->ErrorInfo}";
        }
    } else {
        $msg = "No account found with this email.";
    }
}
include "../html/forgot_password_view.php"; //
?>