<!DOCTYPE html>
<html lang="en">
<head>
    <title>Settings | Crime Detection</title>
    <link rel="stylesheet" type="text/css" href="../css/update_profile_style.css">
</head>
<body>

<div class="sidebar">
    <h2>CRIME REPORT</h2>
    <a href="user_homepage_controller.php" class="nav-btn">Dashboard</a>
    <a href="user_profile_controller.php" class="nav-btn">My History</a>
    <a href="user_logout.php" class="nav-btn logout-btn">Logout</a>
</div>

<div class="main">
    <div class="edit-card">
        <h3>Edit Personal Information</h3>
        <p class="subtitle">Update your contact details.</p>
        <hr>
        <form id="profileForm">
            <div class="input-group">
                <label>Full Name</label>
                <input type="text" id="name" value="<?php echo htmlspecialchars($user['name']); ?>">
            </div>
            <div class="input-group">
                <label>Email (Permanent)</label>
                <input type="text" value="<?php echo htmlspecialchars($user['email']); ?>" disabled class="disabled-input">
            </div>
            <div class="input-group">
                <label>Phone Number</label>
                <input type="text" id="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
            </div>
            <div class="input-group">
                <label>Division</label>
                <select id="division" onchange="loadDistricts()">
                    <option value="">Select Division</option>
                    <?php 
                    $divisions = ["Barishal", "Chattogram", "Dhaka", "Khulna", "Mymensingh", "Rajshahi", "Rangpur", "Sylhet"];
                    foreach($divisions as $d) {
                        $sel = ($user['division'] == $d) ? "selected" : "";
                        echo "<option value='$d' $sel>$d</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="input-group">
                <label>District</label>
                <select id="district">
                    <option value="">Select District</option>
                </select>
            </div>
            <button type="button" onclick="saveProfile()" class="btn-save">Update Profile</button>
            <div id="response-msg" class="response-box"></div>
        </form>
    </div>

    <div class="edit-card">
        <h3>Security Settings</h3>
        <p class="subtitle">Update your login password.</p>
        <hr>
        <div class="input-group">
            <label>Current Password</label>
            <input type="password" id="old_password" placeholder="Verify old password">
        </div>
        <div class="input-group">
            <label>New Password</label>
            <input type="password" id="new_password" placeholder="Min. 6 characters">
        </div>
        <button type="button" onclick="changePassword()" class="btn-save btn-security">Change Password</button>
        <div id="pass-msg" class="response-box"></div>
    </div>
</div>

<script>
function loadDistricts() {
    var div = document.getElementById("division").value;
    var dist = document.getElementById("district");
    dist.innerHTML = "<option value=''>Select District</option>";
    var list = [];
    
    if (div == "Barishal") list = ["Barguna", "Barishal", "Bhola", "Jhalokati", "Patuakhali", "Pirojpur"];
    else if (div == "Chattogram") list = ["Bandarban", "Brahmanbaria", "Chandpur", "Chattogram", "Cumilla", "Cox's Bazar", "Feni", "Khagrachhari", "Lakshmipur", "Noakhali", "Rangamati"];
    else if (div == "Dhaka") list = ["Dhaka", "Faridpur", "Gazipur", "Gopalganj", "Kishoreganj", "Madaripur", "Manikganj", "Munshiganj", "Narayanganj", "Narsingdi", "Rajbari", "Shariatpur", "Tangail"];
    else if (div == "Khulna") list = ["Bagerhat", "Chuadanga", "Jessore", "Jhenaidah", "Khulna", "Kushtia", "Magura", "Meherpur", "Narail", "Satkhira"];
    else if (div == "Mymensingh") list = ["Jamalpur", "Mymensingh", "Netrokona", "Sherpur"];
    else if (div == "Rajshahi") list = ["Bogra", "Chapai Nawabganj", "Joypurhat", "Naogaon", "Natore", "Pabna", "Rajshahi", "Sirajganj"];
    else if (div == "Rangpur") list = ["Dinajpur", "Gaibandha", "Kurigram", "Lalmonirhat", "Nilphamari", "Panchagarh", "Rangpur", "Thakurgaon"];
    else if (div == "Sylhet") list = ["Habiganj", "Moulvibazar", "Sunamganj", "Sylhet"];

    list.forEach(function(d) {
        var opt = document.createElement("option");
        opt.value = d; opt.text = d;
        dist.add(opt);
    });
}

function saveProfile() {
    var msgBox = document.getElementById("response-msg");
    var formData = "ajax_update=true" +
                   "&name=" + encodeURIComponent(document.getElementById("name").value) +
                   "&phone=" + encodeURIComponent(document.getElementById("phone").value) +
                   "&division=" + encodeURIComponent(document.getElementById("division").value) +
                   "&district=" + encodeURIComponent(document.getElementById("district").value);

    var xhr = new XMLHttpRequest();
    // Points back to the controller for handling
    xhr.open("POST", "update_profile_controller.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var res = JSON.parse(xhr.responseText);
            msgBox.style.display = "block";
            msgBox.innerHTML = res.message;
            msgBox.style.background = (res.status == "success") ? "#d4edda" : "#f8d7da";
            msgBox.style.color = (res.status == "success") ? "#155724" : "#721c24";
        }
    };
    xhr.send(formData);
}

function changePassword() {
    var old_p = document.getElementById("old_password").value;
    var new_p = document.getElementById("new_password").value;
    var msgBox = document.getElementById("pass-msg");

    if(!old_p || !new_p) { alert("Fill all password fields"); return; }

    var formData = "ajax_password=true" +
                   "&old_password=" + encodeURIComponent(old_p) +
                   "&new_password=" + encodeURIComponent(new_p);

    var xhr = new XMLHttpRequest();
    // Points back to the controller for handling
    xhr.open("POST", "update_profile_controller.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var res = JSON.parse(xhr.responseText);
            msgBox.style.display = "block";
            msgBox.innerHTML = res.message;
            msgBox.style.background = (res.status == "success") ? "#d4edda" : "#f8d7da";
            msgBox.style.color = (res.status == "success") ? "#155724" : "#721c24";
            if(res.status == "success") {
                document.getElementById("old_password").value = "";
                document.getElementById("new_password").value = "";
            }
        }
    };
    xhr.send(formData);
}

window.onload = function() {
    loadDistricts();
    document.getElementById("district").value = "<?php echo $user['district']; ?>";
};
</script>

</body>
</html>