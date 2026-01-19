<?php
session_start();
include 'db.php';
header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? 0;
$response = ['banned' => false, 'alert' => null];

if ($user_id > 0) {
    // 1. Check User Status (Role)
    $res = mysqli_query($conn, "SELECT role FROM users WHERE id = $user_id");
    if ($row = mysqli_fetch_assoc($res)) {
        if ($row['role'] == 'Banned' || $row['role'] == 'Suspended') {
            $response['banned'] = true;
        }
    }

    // 2. Check Admin Broadcast Alerts
    $alert_res = mysqli_query($conn, "SELECT message, type FROM alert WHERE is_active = 1 LIMIT 1");
    if ($alert_row = mysqli_fetch_assoc($alert_res)) {
        $response['alert'] = [
            'message' => $alert_row['message'],
            'type' => $alert_row['type']
        ];
    }
}
echo json_encode($response);
?>