<?php
session_start();
include '../db/db.php';

// Check Admin Login
if (!isset($_SESSION['is_admin_logged_in'])) {
    header("Location: admin_login_controller.php");
    exit();
}

// Fetch User Data
// Joining with posts table to calculate statistics
$sql = "SELECT 
            u.id as user_id, 
            u.name, 
            u.email, 
            u.phone, 
            u.role,
            u.last_login,
            u.last_logout,
            'N/A' as register_date, 
            COUNT(p.id) as total_posts,
            SUM(CASE WHEN p.status = 'Resolved' THEN 1 ELSE 0 END) as solved_posts,
            MAX(p.created_at) as last_post_date
        FROM users u
        LEFT JOIN posts p ON u.id = p.user_id
        GROUP BY u.id
        ORDER BY u.id DESC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}

// Load the View
include '../html/admin_users_details_view.php';
?>