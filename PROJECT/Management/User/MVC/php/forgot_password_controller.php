<?php
session_start();
include "../db/db_connection.php"; 

mysqli_set_charset($conn, "utf8mb4");

$msg = "";
$step = 1; 
$user_id_to_reset = 0;

if (isset($_POST['verify_btn'])) {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $selected_question = trim($_POST['security_question']); 
    $user_answer = trim($_POST['security_answer']);

    $sql = "SELECT id, security_question, security_answer FROM users WHERE email='$email'";
    $res = mysqli_query($conn, $sql);

    if ($row = mysqli_fetch_assoc($res)) {
        $db_question = trim($row['security_question']);
        $db_answer = trim($row['security_answer']);

        if (strcasecmp($db_question, $selected_question) !== 0) {
            $msg = "The security question selected does not match our records.";
        } 
        else if (password_verify($user_answer, $db_answer)) {
            $step = 2;
            $user_id_to_reset = $row['id'];
        } else {
            $msg = "Incorrect security answer.";
        }
    } else {
        $msg = "No account found with that email address.";
    }
}


if (isset($_POST['reset_btn'])) {
    $uid = mysqli_real_escape_string($conn, $_POST['uid']);
    $new_pass = $_POST['new_password'];
    $c_new_pass = $_POST['confirm_new_password'];

    if (strlen($new_pass) < 6 || !preg_match("/[@$!%*#?&]/", $new_pass)) {
        $msg = "Password must be at least 6 characters with a special symbol.";
        $step = 2;
        $user_id_to_reset = $uid;
    } else if ($new_pass !== $c_new_pass) {
        $msg = "Passwords do not match.";
        $step = 2;
        $user_id_to_reset = $uid;
    } else {
        $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
        if (mysqli_query($conn, "UPDATE users SET password='$hashed_pass' WHERE id='$uid'")) {
            header("Location: login_controller.php?msg=Password reset successful!");
            exit();
        } else {
            $msg = "Database error. Could not reset password.";
        }
    }
}


include "../html/forgot_password_view.php";
?>