<?php
session_start();
include '../db/db.php';
 
if (!isset($_SESSION['is_admin_logged_in'])) {
    header("Location: admin_login_controller.php");
    exit();
}

if (isset($_POST['resolve_report'])) {
    $id = intval($_POST['report_id']);
    $sql = "UPDATE posts SET status='Resolved' WHERE id=$id";
    mysqli_query($conn, $sql);
    
    header("Location: admin_dashboard_controller.php#reports");
    exit();
}
 
$total_reports = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM posts"))['c'];
$pending_reports = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM posts WHERE status='Pending'"))['c'];
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM users"))['c'];
 
$sql = "SELECT p.*, u.name as reporter_name
        FROM posts p
        JOIN users u ON p.user_id = u.id
        ORDER BY p.created_at DESC";
$result = mysqli_query($conn, $sql);

include '../html/admin_dashboard_view.php';
?>