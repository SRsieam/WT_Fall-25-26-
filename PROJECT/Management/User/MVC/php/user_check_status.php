<?php
session_start();
// Pointing to the central database connection model
include "../db/db_connection.php"; 

// Set the header to JSON so the JavaScript can parse it easily
header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? 0;
$response = ['banned' => false, 'alert' => null];

if ($user_id > 0) {
    // Check if the user's role has been changed to Banned or Suspended
    $sql = "SELECT role FROM users WHERE id = $user_id";
    $res = mysqli_query($conn, $sql);
    
    if ($res && $row = mysqli_fetch_assoc($res)) {
        // IMPORTANT: These strings must match EXACTLY what your admin panel saves
        if ($row['role'] === 'Banned' || $row['role'] === 'Suspended') {
            $response['banned'] = true;
        }
    }

    // Check for active site-wide alerts from the admin
    $alert_res = mysqli_query($conn, "SELECT message, type FROM alert WHERE is_active = 1 LIMIT 1");
    if ($alert_row = mysqli_fetch_assoc($alert_res)) {
        $response['alert'] = [
            'message' => $alert_row['message'],
            'type' => $alert_row['type']
        ];
    }
}

// Return data in JSON format for the AJAX listener in your dashboard view
echo json_encode($response);
?>