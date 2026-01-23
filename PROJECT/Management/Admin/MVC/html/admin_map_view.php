<!DOCTYPE html>
<html lang="en">
<head>
    <title>Crime Map View</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" type="text/css" href="../css/admin_map_style.css">
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header">Admin Panel</div>
        <a href="admin_dashboard_controller.php">Dashboard</a>
        <a href="admin_reports_controller.php">Reports</a>
        <a href="admin_users_details_controller.php">Users Details</a>
        <a href="admin_ban_warning_controller.php">Ban or Warning</a>
        <a href="admin_alert_controller.php">Send Alert</a>
        <a href="admin_map_controller.php" class="active">Live Map</a> 
        <a href="admin_logout.php" style="margin-top:auto; background:#d9534f; text-align:center;">Logout</a>
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
        // Start the map centered on Dhaka
        var map = L.map('map').setView([23.8103, 90.4125], 12);

        // Using CartoDB tiles for faster loading and better look
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap contributors &copy; CARTO'
        }).addTo(map);

        // Converting PHP array to JS
        var reports = <?php echo json_encode($markers); ?>;

        reports.forEach(function(report) {
            // Styling the status badge in the popup
            var statusBadge = report.status === 'Resolved' 
                ? '<span class="status-badge badge-resolved">Resolved</span>' 
                : '<span class="status-badge badge-pending">Pending</span>';

            var marker = L.marker([report.lat, report.lng]).addTo(map);

            marker.bindPopup(`
                <div class="popup-content">
                    <b>#${report.id}: ${report.type}</b><br>
                    ${statusBadge}<br>
                    <p style="margin:5px 0 0 0; font-size:0.9rem;">${report.desc}</p>
                    <br>
                    <a href="admin_reports_controller.php" style="color:#007bff; text-decoration:none; font-size:0.85rem;">View Full Details &rarr;</a>
                </div>
            `);
        });

        /* FIX FOR GREY SQUARES: 
           Wait for the browser to finish rendering the layout, 
           then force the map to recalculate its size.
        */
        window.onload = function() {
            setTimeout(function(){ 
                map.invalidateSize(); 
            }, 500);
        };
    </script>
    <script>
    // Existing map init code...
    
    // THIS IS THE FIX: Wait for the browser to finish drawing, then resize map
    window.onload = function() {
        setTimeout(function() {
            map.invalidateSize();
        }, 400); // 400ms delay ensures the CSS layout is complete
    };
</script>

</body>
</html>