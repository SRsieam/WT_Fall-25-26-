<?php
session_start();
include "../db/db_connection.php"; 

$msg = "";
$email = "";

function text_input($data) {
    return trim($data);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = mysqli_real_escape_string($conn, text_input($_POST["email"]));
    $password = text_input($_POST["password"]);

    if (empty($email) || empty($password)) {
        $msg = "Please enter email and password";
    } else {
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);

        if ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $row["password"])) {

                if ($row['role'] == 'Banned') {
                    $msg = "🚫 Access Denied: Your account is permanently banned.";
                } elseif ($row['role'] == 'Suspended') {
                    $msg = "⚠️ Account Suspended: Please contact support.";
                } else {

                    $_SESSION["user_id"] = $row["id"];
                    $_SESSION["user_name"] = $row["name"];
                    $_SESSION["is_user_logged_in"] = true;

                    $user_id = $row["id"];
                    mysqli_query($conn, "UPDATE users SET last_login = NOW() WHERE id = $user_id");

                    header("Location: user_homepage_controller.php"); 
                    exit();
                }
            } else {
                $msg = "Incorrect password";
            }
        } else {
            $msg = "Email not found";
        }
    }
}

include "../html/login_view.php";
?>