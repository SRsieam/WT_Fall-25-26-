<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard | Crime Detection</title>
    <link rel="stylesheet" type="text/css" href="../css/user_homepage_style.css">
</head>
<body>

<div class="sidebar">
    <h2>CRIME REPORT</h2>
    <p>Logged in as:<br><strong><?php echo htmlspecialchars($user_name); ?></strong></p>
    <a href="user_homepage_controller.php" class="nav-btn">Dashboard</a>
    <a href="user_profile_controller.php" class="nav-btn">My Profile</a>
    <a href="user_logout.php" class="logout-link">→ Logout</a>
</div>

<div class="main">
    <div class="card">
        <form method="GET" action="user_homepage_controller.php" class="filter-grid">
            <div><label>Search</label><input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>"></div>
            <div><label>Category</label><select name="filter_cat"><option value="">All</option><?php if($categories_result) { mysqli_data_seek($categories_result, 0); while($cat = mysqli_fetch_assoc($categories_result)): ?><option value="<?php echo $cat['id']; ?>" <?php if($filter_cat == $cat['id']) echo "selected"; ?>><?php echo htmlspecialchars($cat['category_name']); ?></option><?php endwhile; } ?></select></div>
            <div><label>Date</label><input type="date" name="filter_date" value="<?php echo $filter_date; ?>"></div>
            <button type="submit" class="btn-red">Apply</button>
        </form>
    </div>

    <div class="card">
        <div class="report-header">
            <h3>Report an Incident</h3>
            <button type="button" class="pin-btn" onclick="document.getElementById('mapModal').style.display='block'">📍 Pin Location</button>
        </div>
        <p id="pin-text">Location not selected*</p>
        <?php if($msg): ?><p class="error-text"><?php echo $msg; ?></p><?php endif; ?>
        
        <form method="post" enctype="multipart/form-data" action="user_homepage_controller.php">
            <input type="hidden" name="lat" id="lat">
            <input type="hidden" name="lng" id="lng">
            <textarea name="content" rows="2" placeholder="Describe the crime situation..." required></textarea>
            <select name="category_id" required>
                <option value="">-- Choose Category --</option>
                <?php if($categories_result) { mysqli_data_seek($categories_result, 0); while($cat = mysqli_fetch_assoc($categories_result)): ?>
                    <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['category_name']); ?></option>
                <?php endwhile; } ?>
            </select>
            <input type="file" name="image">
            <button type="submit" name="submit_post" class="btn-red">Broadcast Alert</button>
        </form>
    </div>

    <h2>Recent Alerts Feed</h2>
    <?php if($posts) { while ($row = mysqli_fetch_assoc($posts)): ?>
        <div class="card">
            <div class="post-header">
                <strong><?php echo htmlspecialchars($row["name"]); ?></strong>
                <span class="tag"><?php echo htmlspecialchars($row["category_name"] ?? 'General'); ?></span>
            </div>
            <p><?php echo nl2br(htmlspecialchars($row["content"])); ?></p>
            <?php if (!empty($row["image"])): ?><img src="../uploads/<?php echo $row["image"]; ?>" class="post-image"><?php endif; ?>
            <div class="post-footer">
                <small><?php echo $row["created_at"]; ?></small>
                <div>
                    <?php if(!empty($row['lat']) && $row['lat'] != 0): ?>
                        <a href="https://maps.google.com/?q=<?php echo $row['lat']; ?>,<?php echo $row['lng']; ?>" target="_blank" class="map-link">📍 View Map</a>
                    <?php endif; ?>
                    <span class="status <?php echo $row['status']; ?>"><?php echo $row['status']; ?></span>
                </div>
            </div>
        </div>
    <?php endwhile; } ?>
</div>

<div id="mapModal"><div class="modal-content"><h3>Select Location</h3><div id="map"></div><button type="button" class="btn-red" onclick="document.getElementById('mapModal').style.display='none'">Confirm</button></div></div>

<div id="alertOverlay">
    <div id="alertCard" class="alert-popup-card">
        <h2 id="alertTitle">ADMIN BROADCAST</h2>
        <p id="alertMessage"></p>
        <button class="btn-dismiss" onclick="closeAlert()">Dismiss</button>
    </div>
</div>

<div class="chat-sidebar">
    <div class="chat-header">Global Community Chat</div>
    <div id="chat-box"></div>
    <div class="chat-input-area">
        <input type="text" id="chat-input" placeholder="Type..." onkeypress="if(event.key==='Enter') sendMessage()">
        <button class="btn-red" onclick="sendMessage()">Send</button>
    </div>
</div>

<script>
let map, marker, lastAlertMessage = "";

// Logic remains identical to raw code
function initMap() {
    map = new google.maps.Map(document.getElementById("map"), { center: {lat: 23.8103, lng: 90.4125}, zoom: 12 });
    map.addListener("click", (e) => {
        if (marker) marker.setMap(null);
        marker = new google.maps.Marker({ position: e.latLng, map: map });
        document.getElementById("lat").value = e.latLng.lat();
        document.getElementById("lng").value = e.latLng.lng();
        document.getElementById("pin-text").innerText = "📍 Location Pinned Successfully";
        document.getElementById("pin-text").style.color = "#2ecc71";
    });
}

function performSecurityCheck() {
    var xhr = new XMLHttpRequest();
    // Path updated for MVC folder
    xhr.open("GET", "user_check_status.php", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            try {
                var data = JSON.parse(xhr.responseText);
                if (data.banned) {
                    alert("SECURITY ALERT: Your account has been deactivated. Logging out...");
                    window.location.href = "user_logout.php";
                    return;
                }
                if (data.alert) {
                    if (data.alert.message !== lastAlertMessage) {
                        document.getElementById("alertMessage").innerText = data.alert.message;
                        document.getElementById("alertTitle").innerText = data.alert.type.toUpperCase() + " ALERT";
                        document.getElementById("alertCard").className = "alert-popup-card header-" + data.alert.type;
                        document.getElementById("alertOverlay").style.display = "block";
                        lastAlertMessage = data.alert.message;
                    }
                } else { closeAlert(); }
            } catch(e) {}
        }
    };
    xhr.send();
}

function closeAlert() { document.getElementById("alertOverlay").style.display = "none"; }

function fetchChat() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "user_chat_controller.php", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            try {
                var data = JSON.parse(xhr.responseText);
                var html = "";
                data.forEach(function(m) {
                    html += "<div class='msg-bubble'><b>" + m.name + ":</b><span>" + m.message + "</span></div>";
                });
                document.getElementById("chat-box").innerHTML = html;
            } catch(e) {}
        }
    };
    xhr.send();
}

function sendMessage() {
    var el = document.getElementById("chat-input");
    if (el.value.trim() == "") return;
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "user_chat_controller.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("message=" + encodeURIComponent(el.value));
    el.value = ""; 
    setTimeout(fetchChat, 500);
}

setInterval(fetchChat, 3000);
setInterval(performSecurityCheck, 5000);
window.onload = function() { fetchChat(); performSecurityCheck(); };
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBsnn0ZmcSODM68Vx2LBCHp3MvpKKXR_kQ&callback=initMap" async defer></script>
</body>
</html>