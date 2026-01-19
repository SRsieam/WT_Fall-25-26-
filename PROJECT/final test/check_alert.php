<?php
include 'db.php';
header('Content-Type: application/json');

// Fetch the latest active alert
$sql = "SELECT message, type FROM alert WHERE is_active = 1 LIMIT 1";
$result = mysqli_query($conn, $sql);

if ($row = mysqli_fetch_assoc($result)) {
    echo json_encode([
        'active' => true, 
        'message' => $row['message'], 
        'type' => $row['type']
    ]);
} else {
    echo json_encode(['active' => false]);
}
?>