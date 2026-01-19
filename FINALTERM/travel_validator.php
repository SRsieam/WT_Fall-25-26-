<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Clean inputs using your helper function
    $name = text_input($_POST["name"]);
    $passport = text_input($_POST["passport"]);
    $dest = $_POST["destination"];
    $tdate = $_POST["travel_date"];
    $insurance = $_POST["insurance"] ?? "";

    // Save inputs in session so the user doesn't have to re-type them
    $_SESSION['name'] = $name;
    $_SESSION['passport'] = $passport;

    $hasError = false;

    // 1. Name Validation
    if (empty($name)) {
        $_SESSION['nameerror'] = "Name is required";
        $hasError = true;
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $name)) {
        $_SESSION['nameerror'] = "Letters only please";
        $hasError = true;
    }

    // 2. Passport Validation (Alphanumeric, min 6)
    if (empty($passport)) {
        $_SESSION['passerror'] = "Passport is required";
        $hasError = true;
    } elseif (!ctype_alnum($passport) || strlen($passport) < 6) {
        $_SESSION['passerror'] = "Must be alphanumeric and 6+ chars";
        $hasError = true;
    }

    // 3. Destination Validation
    if (empty($dest)) {
        $_SESSION['desterror'] = "Select a destination";
        $hasError = true;
    }

    // 4. Date Validation (Not in past)
    if (empty($tdate)) {
        $_SESSION['dateerror'] = "Date is required";
        $hasError = true;
    } elseif ($tdate < date("Y-m-d")) {
        $_SESSION['dateerror'] = "Date cannot be in the past";
        $hasError = true;
    }

    // 5. Insurance Validation
    if (empty($insurance)) {
        $_SESSION['inserror'] = "Selection required";
        $hasError = true;
    }

    if (!$hasError) {
        $_SESSION['success'] = true;
    }

    // Always redirect back to the form page
    header("Location: travel.php");
    exit();
}

function text_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}
?>