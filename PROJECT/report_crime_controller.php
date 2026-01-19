<?php
session_start();
include "../db/db_connection.php"; // Path to Model

// Authentication Check
$user_id = $_SESSION["user_id"] ?? 0;
if ($user_id == 0) { 
    header("Location: user_login_controller.php"); 
    exit(); 
}

$success = $error = "";

if (isset($_POST['submit_report'])) {
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $lat = mysqli_real_escape_string($conn, $_POST['lat']);
    $lng = mysqli_real_escape_string($conn, $_POST['lng']);
    $cat_id = mysqli_real_escape_string($conn, $_POST['category_id']);

    if (empty($lat) || empty($lng)) {
        $error = "Please click on the map to select a location!";
    } else {
        // Logic remains "same to same" as provided
        $sql = "INSERT INTO posts (user_id, content, category_id, lat, lng, status) 
                VALUES ('$user_id', '$content', '$cat_id', '$lat', '$lng', 'Pending')";
        
        if (mysqli_query($conn, $sql)) {
            // Radius-based notification for nearby users (2.0 km)
            $radius = 2.0; 
            $nearby_sql = "SELECT id FROM users WHERE id != '$user_id' AND 
                (6371 * acos(cos(radians($lat)) * cos(radians(user_lat)) * cos(radians(user_lng) - radians($lng)) + sin(radians($lat)) * sin(radians(user_lat)))) < $radius";
            
            $result = mysqli_query($conn, $nearby_sql);
            while($row = mysqli_fetch_assoc($result)) {
                $near_id = $row['id'];
                mysqli_query($conn, "INSERT INTO notifications (user_id, message) 
                                     VALUES ('$near_id', 'Danger! Crime reported near you.')");
            }
            $success = "Report submitted and nearby users notified!";
        } else {
            $error = "Database Error: " . mysqli_error($conn);
        }
    }
}

// Fetch categories for the dropdown
$cat_res = mysqli_query($conn, "SELECT * FROM categories ORDER BY category_name ASC");

// Load the View
include "../html/report_crime_view.php";
?>