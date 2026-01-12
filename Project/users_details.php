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
        th { 
            padding: 10px; 
            border-bottom: 1px solid #ddd; 
            text-align: center;
            background: #343a40; 
            color: white; }
        td {
            padding: 10px; 
            border-bottom: 1px solid #ddd; 
            text-align: center; }
        tr:hover { 
            background: #f1f1f1; }


        .sidebar { 
            width: 250px; 
            background: #343a40; 
            color: white; 
            display: flex; 
            flex-direction: column; 
            flex-shrink: 0; }
        .sidebar-header { 
            padding: 20px; 
            font-weight: bold; 
            background: #212529; 
            text-align: center; 
            font-size: 1.2rem; }
        .sidebar a { 
            display: block; 
            padding: 15px 20px; 
            color: #c2c7d0; 
            text-decoration: none; 
            border-bottom: 1px solid #3f474e; 
            transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { 
            background: #007bff; 
            color: white; 
            padding-left: 25px; }
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
            padding: 5px 14px;
            border-radius: 4px;
            font-size: 0.9rem;
            transition: 0.3s;}

.back-btn:hover {
    background: #007bff;
}
    </style>

</head>
<body>
    <h2> All Users Details</h2>
<a href="dashboard.php" class="back-btn"> Back</a>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Register Date</th>
            <th>Total Posts</th>
            <th>Solved Posts</th>
            <th>Pending Posts</th>
            <th>Last Post Date</th>
            <th>Banned</th>
        </tr>
    </thead>
 
</table>

</body>
</html>
