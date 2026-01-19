<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "registration_db";
$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Connect lost" . mysqli_connect_error());
}
?>