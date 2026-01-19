<?php
session_start();
include '../db/db.php'; 

// Check Admin Login
if (!isset($_SESSION['is_admin_logged_in'])) {
    header("Location: admin_login_controller.php"); // Redirect to login controller
    exit();
}

$msg = "";

// --- Handle "Approve" Action ---
if (isset($_POST['approve_report'])) {
    $id = intval($_POST['report_id']);
    $sql = "UPDATE posts SET status='Resolved' WHERE id=$id";
    if (mysqli_query($conn, $sql)) {
        $msg = "<div class='alert success'>Report #$id marked as Resolved</div>";
    }
}

// --- Handle "Delete" Action ---
if (isset($_POST['delete_report'])) {
    $id = intval($_POST['report_id']);
    $sql = "DELETE FROM posts WHERE id=$id";
    if (mysqli_query($conn, $sql)) {
        $msg = "<div class='alert danger'>Report #$id has been deleted</div>";
    }
}

// --- Fetch All Reports ---
$sql = "SELECT p.*, u.name as reporter_name, u.email as reporter_email, c.category_name 
        FROM posts p 
        JOIN users u ON p.user_id = u.id 
        LEFT JOIN categories c ON p.category_id = c.id
        ORDER BY p.created_at DESC";
$result = mysqli_query($conn, $sql);

// Load the View
include '../html/admin_reports_view.php';
?>