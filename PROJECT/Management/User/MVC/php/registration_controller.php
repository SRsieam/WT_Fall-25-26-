<?php
// Include the database model
include "../db/db_connection.php"; 

$msg = "";
$success = false;

$name = $email = $division = $district = $dob = $phone = $security_question = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Logic remains identical to raw code
    $name      = mysqli_real_escape_string($conn, trim($_POST["name"] ?? ""));
    $email     = mysqli_real_escape_string($conn, trim($_POST["email"] ?? ""));
    $division  = mysqli_real_escape_string($conn, trim($_POST["division"] ?? ""));
    $district  = mysqli_real_escape_string($conn, trim($_POST["district"] ?? ""));
    $dob       = mysqli_real_escape_string($conn, trim($_POST["dob"] ?? ""));
    $phone     = mysqli_real_escape_string($conn, trim($_POST["phone"] ?? ""));
    $password  = $_POST["password"] ?? "";
    $cpassword = $_POST["cpassword"] ?? "";
    
    $security_question = mysqli_real_escape_string($conn, $_POST["security_question"] ?? "");
    $security_answer   = trim($_POST["security_answer"] ?? "");

    // Existing Validations
    if ($name == "" || $email == "" || $dob == "" || $phone == "" || $security_answer == "" || $security_question == "") {
        $msg = "All required fields must be filled.";
    }
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "Enter a valid email address.";
    }
    else if (strlen($password) < 6 || !preg_match("/[@$!%*#?&]/", $password)) {
        $msg = "Password must be at least 6 characters and include a special symbol.";
    }
    else if ($password !== $cpassword) {
        $msg = "Passwords do not match.";
    }
    else {
        $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
        if (mysqli_num_rows($check) > 0) {
            $msg = "This email is already registered.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $hash_answer = password_hash($security_answer, PASSWORD_DEFAULT);

            // Database insertion logic
            $sql = "INSERT INTO users 
                    (name, email, division, district, dob, phone, password, security_question, security_answer)
                    VALUES 
                    ('$name', '$email', '$division', '$district', '$dob', '$phone', '$hash', '$security_question', '$hash_answer')";

            if (mysqli_query($conn, $sql)) {
                $msg = "Registration successful!";
                $success = true;
                $name = $email = $division = $district = $dob = $phone = "";
            } else {
                $msg = "Database error: " . mysqli_error($conn);
            }
        }
    }
}

// Load the View
include "../html/registration_view.php";
?>