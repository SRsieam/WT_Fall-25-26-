<?php
session_start();
include 'db.php'; 
?>

<!DOCTYPE html>
<head>
    <title>Manage All Reports</title>
    <style>
        body { margin: 0; font-family: 'Segoe UI', sans-serif; background: #f4f6f9; display: flex; height: 100vh; }
        
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
        .sidebar a:hover { 
            background: #007bff; 
            color: white; 
            padding-left: 25px; }
        /*.sidebar a.active { 
            background: #007bff; 
            color: white; }*/

        .main-content { 
            flex-grow: 1; 
            padding: 30px; 
            overflow-y: auto; }
        h2 { 
            border-bottom: 2px solid #ddd; 
            padding-bottom: 10px; 
            color: #333; }

    </style>
</head>
<body>

</body>
</html>