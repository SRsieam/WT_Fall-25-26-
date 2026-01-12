<?php
session_start();
include 'db.php'; 

if (!isset($_SESSION['is_admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

$msg = "";

if (isset($_POST['approve_report'])) {
    $id = intval($_POST['report_id']);
    $sql = "UPDATE reports SET status='Resolved' WHERE report_id=$id";
    if (mysqli_query($conn, $sql)) {
        $msg = "<div class='alert success'>Report #$id marked as Resolved</div>";
    }
}

if (isset($_POST['delete_report'])) {
    $id = intval($_POST['report_id']);
    $sql = "DELETE FROM reports WHERE report_id=$id";
    if (mysqli_query($conn, $sql)) {
        $msg = "<div class='alert danger'>Report #$id has been deleted</div>";
    }
}

$sql = "SELECT r.*, u.name as reporter_name, u.email as reporter_email 
        FROM reports r 
        JOIN users u ON r.user_id = u.user_id 
        ORDER BY r.created_at DESC";
$result = mysqli_query($conn, $sql);
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

    <div class="sidebar">
        <div class="sidebar-header">Admin Panel</div>
        <a href="dashboard.php">Dashboard</a>
        <a href="reports.php" class="active">All Reports</a>
        <a href="users_details.php">Users Details</a>
        <a href="ban_warning.php">Ban or Warning</a>
        <a href="alert.php">Send Alert</a>
        <a href="admin_logout.php" style="margin-top:auto; background:#d9534f; text-align:center;">Logout</a>
    </div>

    

</body>
</html>