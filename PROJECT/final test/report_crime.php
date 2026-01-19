<?php
session_start();
include "db.php";

$user_id = $_SESSION["user_id"] ?? 0;
if ($user_id == 0) { header("Location: loginuser.php"); exit(); }

$success = $error = "";

if (isset($_POST['submit_report'])) {
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $lat = mysqli_real_escape_string($conn, $_POST['lat']);
    $lng = mysqli_real_escape_string($conn, $_POST['lng']);
    $cat_id = mysqli_real_escape_string($conn, $_POST['category_id']);

    if (empty($lat) || empty($lng)) {
        $error = "Please click on the map to select a location!";
    } else {
        $sql = "INSERT INTO posts (user_id, content, category_id, lat, lng, status) VALUES ('$user_id', '$content', '$cat_id', '$lat', '$lng', 'Pending')";
        
        if (mysqli_query($conn, $sql)) {
            $radius = 2.0; 
            $nearby_sql = "SELECT id FROM users WHERE id != '$user_id' AND 
                (6371 * acos(cos(radians($lat)) * cos(radians(user_lat)) * cos(radians(user_lng) - radians($lng)) + sin(radians($lat)) * sin(radians(user_lat)))) < $radius";
            
            $result = mysqli_query($conn, $nearby_sql);
            while($row = mysqli_fetch_assoc($result)) {
                $near_id = $row['id'];
                mysqli_query($conn, "INSERT INTO notifications (user_id, message) VALUES ('$near_id', 'Danger! Crime reported near you.')");
            }
            $success = "Report submitted and nearby users notified!";
        } else {
            $error = mysqli_error($conn);
        }
    }
}

$cat_res = mysqli_query($conn, "SELECT * FROM categories ORDER BY category_name ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Report Crime | Map Selection</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f7f6; margin: 0; display: flex; }
        .sidebar { width: 250px; background: #1a252f; color: white; height: 100vh; position: fixed; padding: 20px; box-sizing: border-box; }
        .main { margin-left: 250px; padding: 40px; width: 100%; box-sizing: border-box; }
        #map { height: 400px; width: 100%; border-radius: 10px; border: 2px solid #34495e; margin-bottom: 20px; }
        .form-container { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .nav-btn { display: block; background: #34495e; color: white; padding: 12px; text-decoration: none; margin-bottom: 10px; border-radius: 5px; text-align: center; font-size: 14px; }
        textarea, select { width: 100%; padding: 15px; border: 1px solid #ccc; border-radius: 8px; box-sizing: border-box; margin-bottom: 15px; font-family: inherit; }
        .sub-btn { background: #e74c3c; color: white; border: none; padding: 15px; width: 100%; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: bold; }
        .sub-btn:hover { background: #c0392b; }
    </style>
    <script>
        let marker;
        function initMap() {
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
    <a href="homepage.php" class="nav-btn">Dashboard</a>
    <a href="profile.php" class="nav-btn">My Profile</a>
    <a href="report_crime.php" class="nav-btn" style="background: #e74c3c;">Report Crime</a>
    <a href="logout.php" class="nav-btn" style="margin-top: 50px; background: #c0392b;">Logout</a>
</div>

<div class="main">
    <div class="form-container">
        <h2>Report New Incident</h2>
        <p style="font-size: 14px; color: #666;">1. Click on the map to select the location.</p>
        <div id="map"></div>
        <form method="post">
            <input type="hidden" name="lat" id="lat">
            <input type="hidden" name="lng" id="lng">
            <p style="font-size: 14px; color: #666;">2. Incident Details:</p>
            <select name="category_id" required>
                <option value="">-- Select Category --</option>
                <?php while($c = mysqli_fetch_assoc($cat_res)): ?>
                    <option value="<?php echo $c['id']; ?>"><?php echo $c['category_name']; ?></option>
                <?php endwhile; ?>
            </select>
            <textarea name="content" rows="4" placeholder="Describe what happened..." required></textarea>
            <input type="submit" name="submit_report" value="Submit Report & Notify Neighbors" class="sub-btn">
        </form>
        <?php if($success): ?><p style="color:green; font-weight:bold;"><?php echo $success; ?></p><?php endif; ?>
        <?php if($error): ?><p style="color:red; font-weight:bold;"><?php echo $error; ?></p><?php endif; ?>
    </div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBsnn0ZmcSODM68Vx2LBCHp3MvpKKXR_kQ&callback=initMap" async defer></script>
</body>
</html>