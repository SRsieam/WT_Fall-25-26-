<?php
$conn = mysqli_connect("localhost", "root", "", "crimealert");

if (!$conn) {
    die("Database connection failed");
}

function test_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return mysqli_real_escape_string($conn, $data);
}
