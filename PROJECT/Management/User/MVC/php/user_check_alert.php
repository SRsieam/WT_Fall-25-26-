<?php
// Include the database connection from the model folder
include '../db/db_connection.php';

// Set the header to JSON so the JavaScript can parse it easily
header('Content-Type: application/json');

// Fetch the latest active alert from the 'alert' table
$sql = "SELECT message, type FROM alert WHERE is_active = 1 LIMIT 1";
$result = mysqli_query($conn, $sql);

if ($row = mysqli_fetch_assoc($result)) {
    // If an alert is found, return the message and the type (info, danger, etc.)
    echo json_encode([
        'active' => true, 
        'message' => $row['message'], 
        'type' => $row['type']
    ]);
} else {
    // If no active alert exists, return false
    echo json_encode(['active' => false]);
}
?>