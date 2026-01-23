<?php
session_start();

/* FIXING DATABASE ERROR: 
   Points to the 'db' folder inside Admin/MVC/. 
   Ensure db_connection.php is copied into that folder!
*/
include "../db/db_connection.php"; 

// Only allow logged-in admins to see this
if (!isset($_SESSION['is_admin_logged_in'])) {
    header("Location: admin_login_controller.php");
    exit();
}

// Fetching all reports that have GPS coordinates
$sql = "SELECT p.*, c.category_name 
        FROM posts p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.lat IS NOT NULL AND p.lng IS NOT NULL 
        AND p.lat != 0 AND p.lng != 0";

$result = mysqli_query($conn, $sql);

$markers = [];
while ($row = mysqli_fetch_assoc($result)) {
    // Preparing data for the Leaflet map
    $markers[] = [
        'id' => $row['id'],
        'lat' => $row['lat'],
        'lng' => $row['lng'],
        'type' => $row['category_name'] ?? 'General',
        'desc' => substr(htmlspecialchars($row['content']), 0, 100) . '...', 
        'status' => $row['status']
    ];
}

// Loading the View file
include "../html/admin_map_view.php";
?>