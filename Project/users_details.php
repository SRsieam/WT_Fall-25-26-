<?php
session_start();
include 'db.php';

if (!isset($_SESSION['is_admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Users Details</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #f4f6f9; padding: 20px; }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            background: white; }
        th, td { 
            padding: 10px; 
            border-bottom: 1px solid #ddd; 
            text-align: center; }
        th { 
            background: #343a40; 
            color: white; }
        .status-active { 
            color: green; 
            font-weight: bold; }
        .status-banned { 
            color: red; 
            font-weight: bold; }
        .badge { 
            padding: 4px 8px; 
            border-radius: 10px; 
            font-size: 0.85rem; }
        .badge-solved { 
            background: #d4edda; 
            color: #155724; }
        .badge-pending { 
            background: #fff3cd; 
            color: #856404; }
        .back-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            text-decoration: none;
            background: #343a40;
            color: white;
            padding: 8px 14px;
            border-radius: 4px;
            font-size: 0.9rem;
            transition: 0.3s;}

.back-btn:hover {
    background: #007bff;
}
    </style>

</head>
<body>

</body>
</html>
