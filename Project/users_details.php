<?php
session_start();
include 'db.php';

if (!isset($_SESSION['is_admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}


