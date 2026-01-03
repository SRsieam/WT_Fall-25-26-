<?php
include "../db/db.php";

$msg = "";
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $name      = $_POST["name"] ?? "";
    $email     = $_POST["email"] ?? "";
    $division  = $_POST["division"] ?? "";
    $district  = $_POST["district"] ?? "";
    $dob       = $_POST["dob"] ?? "";
    $phone     = $_POST["phone"] ?? "";
    $password  = $_POST["password"] ?? "";
    $cpassword = $_POST["cpassword"] ?? "";

    if (str_word_count($name) < 2) {
        $msg = "Name must contain first name and last name";
    }
    else if (strpos($email, "@") === false || strpos($email, ".com") === false) {
        $msg = "Email must contain @ and .com";
    }
    else if (!ctype_digit($phone) || strlen($phone) < 11) {
        $msg = "Phone number must be numeric and at least 11 digits";
    }
    else if (!preg_match("/[@$!%*#?&]/", $password)) {
        $msg = "Password must contain at least one special character";
    }
    else if ($password !== $cpassword) {
        $msg = "Password and Confirm Password do not match";
    }
    else {
        $hashPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (name, email, division, district, dob, phone, password)
                VALUES ('$name', '$email', '$division', '$district', '$dob', '$phone', '$hashPassword')";

        if (mysqli_query($conn, $sql)) {
            $msg = "Registration successful!";
            $success = true;
        } else {
            $msg = "Database Error!";
        }
    }
}

include "../html/register.php";
