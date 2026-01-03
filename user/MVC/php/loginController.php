<?php
session_start();
include "../db/db.php";

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $email    = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if (empty($email) || empty($password)) {
        $msg = "Please enter email and password";
    }
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "Invalid email format";
    }
    else {
        $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row["password"])) {
                $_SESSION["user_id"]   = $row["id"];
                $_SESSION["user_name"] = $row["name"];
                header("Location: ../html/homepage.php");
                exit();
            } else {
                $msg = "Incorrect email or password";
            }
        } else {
            $msg = "Incorrect email or password";
        }

        $stmt->close();
    }
}

include "../html/login.php";
