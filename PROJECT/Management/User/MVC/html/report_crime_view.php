<!DOCTYPE html>
<html>
<head>
    <title>Report Crime | Map Selection</title>
    <link rel="stylesheet" type="text/css" href="../css/report_crime_style.css">
    <script>
        let marker;
        function initMap() {
            // Logic remains identical to your original JavaScript
            const dhaka = { lat: 23.8103, lng: 90.4125 };
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 13,
                center: dhaka,
            });
            map.addListener("click", (event) => {
                addMarker(event.latLng, map);
            });
        }
        function addMarker(location, map) {
            if (marker) { marker.setMap(null); }
            marker = new google.maps.Marker({
                position: location,
                map: map,
                animation: google.maps.Animation.DROP
            });
            document.getElementById("lat").value = location.lat();
            document.getElementById("lng").value = location.lng();
        }
    </script>
</head>
<body>

<div class="sidebar">
    <h2 style="color:#e74c3c;">CRIME REPORT</h2>
    <a href="user_homepage_controller.php" class="nav-btn">Dashboard</a>
    <a href="user_profile_controller.php" class="nav-btn">My Profile</a>
    <a href="report_crime_controller.php" class="nav-btn active-nav">Report Crime</a>
    <a href="user_logout.php" class="nav-btn logout-link">Logout</a>
</div>

<div class="main">
    <div class="form-container">
        <h2>Report New Incident</h2>
        <p class="instruction">1. Click on the map to select the location.</p>
        <div id="map"></div>
        
        <form method="post" action="report_crime_controller.php">
            <input type="hidden" name="lat" id="lat">
            <input type="hidden" name="lng" id="lng">
            
            <p class="instruction">2. Incident Details:</p>
            <select name="category_id" required>
                <option value="">-- Select Category --</option>
                <?php while($c = mysqli_fetch_assoc($cat_res)): ?>
                    <option value="<?php echo $c['id']; ?>"><?php echo $c['category_name']; ?></option>
                <?php endwhile; ?>
            </select>
            
            <textarea name="content" rows="4" placeholder="Describe what happened..." required></textarea>
            <input type="submit" name="submit_report" value="Submit Report & Notify Neighbors" class="sub-btn">
        </form>
        
        <?php if($success): ?><p class="success-text"><?php echo $success; ?></p><?php endif; ?>
        <?php if($error): ?><p class="error-text"><?php echo $error; ?></p><?php endif; ?>
    </div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap" async defer></script>
</body>
</html>