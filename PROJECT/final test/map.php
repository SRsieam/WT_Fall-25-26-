<?php
session_start();
include 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['is_admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch all reports that have valid coordinates
// We join with 'categories' to get the crime name (e.g., Robbery, Theft)
$sql = "SELECT p.*, c.category_name 
        FROM posts p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.lat IS NOT NULL AND p.lng IS NOT NULL 
        AND p.lat != 0 AND p.lng != 0";

$result = mysqli_query($conn, $sql);

$markers = [];
while ($row = mysqli_fetch_assoc($result)) {
    // Sanitize data for JavaScript
    $markers[] = [
        'id' => $row['id'],
        'lat' => $row['lat'],
        'lng' => $row['lng'],
        'type' => $row['category_name'] ?? 'General',
        'desc' => substr(htmlspecialchars($row['content']), 0, 100) . '...', // Shorten description
        'status' => $row['status']
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Crime Map View</title>
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        body { margin: 0; font-family: 'Segoe UI', sans-serif; background: #f4f6f9; display: flex; height: 100vh; }
        
        .sidebar { width: 250px; background: #343a40; color: white; display: flex; flex-direction: column; flex-shrink: 0; }
        .sidebar-header { padding: 20px; font-weight: bold; background: #212529; text-align: center; font-size: 1.2rem; }
        .sidebar a { display: block; padding: 15px 20px; color: #c2c7d0; text-decoration: none; border-bottom: 1px solid #3f474e; transition: 0.3s; }
        .sidebar a:hover { background: #007bff; color: white; padding-left: 25px; }
        .sidebar a.active { background: #007bff; color: white; }

        .main-content { flex-grow: 1; display: flex; flex-direction: column; }
        
        /* Map Container */
        #map { flex-grow: 1; width: 100%; height: 100%; z-index: 1; }
        
        .map-header {
            padding: 15px 20px;
            background: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            z-index: 2;
            display: flex; justify-content: space-between; align-items: center;
        }
        
        /* Popup Styles */
        .popup-content b { color: #333; font-size: 1.1em; }
        .status-badge { display: inline-block; padding: 3px 8px; border-radius: 4px; color: white; font-size: 0.8em; margin-top: 5px;}
        .badge-pending { background: #ffc107; color: #333; }
        .badge-resolved { background: #28a745; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header">Admin Panel</div>
        <a href="dashboard.php">Dashboard</a>
        <a href="reports.php">Reports</a>
        <a href="users_details.php">Users Details</a>
        <a href="ban_warning.php">Ban or Warning</a>
        <a href="alert.php">Send Alert</a>
        <a href="admin_map.php" class="active">Live Map</a> <a href="admin_logout.php" style="margin-top:auto; background:#d9534f; text-align:center;">Logout</a>
    </div>

    <div class="main-content">
        <div class="map-header">
            <h3>📍 Live Crime Locations</h3>
            <span style="color:#666; font-size:0.9rem;">Showing <?php echo count($markers); ?> reports on map</span>
        </div>
        
        <div id="map"></div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // --- A. Initialize the Map ---
        // Default View: Dhaka coordinates (23.8103, 90.4125). Zoom Level: 12
        var map = L.map('map').setView([23.8103, 90.4125], 12);

        // --- B. Add OpenStreetMap Tiles (The visual map layer) ---
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // --- C. Load Markers from PHP Database ---
        var reports = <?php echo json_encode($markers); ?>;

        reports.forEach(function(report) {
            // Determine Marker Color or Badge based on status
            var statusBadge = report.status === 'Resolved' 
                ? '<span class="status-badge badge-resolved">Resolved</span>' 
                : '<span class="status-badge badge-pending">Pending</span>';

            // Create Marker
            var marker = L.marker([report.lat, report.lng]).addTo(map);

            // Add Click Popup
            marker.bindPopup(`
                <div class="popup-content">
                    <b>#${report.id}: ${report.type}</b><br>
                    ${statusBadge}<br>
                    <p style="margin:5px 0 0 0; font-size:0.9rem;">${report.desc}</p>
                    <br>
                    <a href="reports.php" style="color:#007bff; text-decoration:none; font-size:0.85rem;">View Full Details &rarr;</a>
                </div>
            `);
        });

    </script>

</body>
</html>