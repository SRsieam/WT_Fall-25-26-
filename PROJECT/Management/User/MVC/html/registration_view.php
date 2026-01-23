<!DOCTYPE html>
<html>
<head>
    <title>Registration | Crime Detection</title>
    <link rel="stylesheet" type="text/css" href="../css/registration_style.css">
</head>
<body>
<div class="form-box">
    <h2>REGISTRATION</h2>

    <?php if(!empty($msg)): ?>
        <div class="status-msg <?php echo $success ? 'success' : 'error'; ?>">
            <?php echo $msg; ?>
        </div>
    <?php endif; ?>

    <form method="post" action="../php/registration_controller.php" autocomplete="off">
        <div class="input-group">
            <input type="text" name="name" placeholder="Full Name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
        </div>

        <div class="input-group">
            <input type="email" name="email" placeholder="Email Address" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
        </div>

        <div class="input-group" style="display: flex; gap: 10px;">
            <select name="division" id="division" onchange="loadDistricts()" required>
                <option value="">Select Division</option>
                <?php 
                $divs = ["Dhaka", "Chattogram", "Rajshahi", "Khulna", "Barishal", "Sylhet", "Rangpur", "Mymensingh"];
                foreach($divs as $d) {
                    $sel = (isset($division) && $division == $d) ? "selected" : "";
                    echo "<option $sel>$d</option>";
                }
                ?>
            </select>
            <select name="district" id="district" required>
                <option value="">District</option>
            </select>
        </div>

        <div class="input-group">
            <label class="dob-label">Date of Birth</label>
            <input type="date" name="dob" value="<?php echo $dob ?? ''; ?>" required>
        </div>

        <div class="input-group">
            <input type="text" name="phone" placeholder="Phone Number (11 Digits)" value="<?php echo htmlspecialchars($phone ?? ''); ?>" required>
        </div>

        <div class="input-group">
            <input type="password" name="password" placeholder="Password" required autocomplete="new-password">
        </div>

        <div class="input-group">
            <input type="password" name="cpassword" placeholder="Confirm Password" required>
        </div>

        <button type="submit">Create Account</button>
        
        <div class="footer">
            Already have an account? <a href="../php/login_controller.php">Login</a>
        </div>
    </form>
</div>

<script>
function loadDistricts() {
    var div = document.getElementById("division").value;
    var dist = document.getElementById("district");
    dist.innerHTML = "<option value=''>Select District</option>";
    var list = [];

    if (div == "Dhaka") list = ["Dhaka City", "Gazipur", "Narayanganj", "Tangail", "Gopalganj"];
    else if (div == "Chattogram") list = ["Chattogram City", "Cox's Bazar", "Cumilla", "Feni"];
    else if (div == "Rajshahi") list = ["Rajshahi City", "Bogura", "Pabna", "Sirajganj"];
    else if (div == "Khulna") list = ["Khulna City", "Jashore", "Satkhira", "Bagerhat"];
    else if (div == "Barishal") list = ["Barishal City", "Bhola", "Patuakhali"];
    else if (div == "Sylhet") list = ["Sylhet City", "Moulvibazar", "Habiganj"];
    else if (div == "Rangpur") list = ["Rangpur City", "Dinajpur", "Kurigram"];
    else if (div == "Mymensingh") list = ["Mymensingh City", "Jamalpur", "Netrokona"];

    list.forEach(function(d) {
        var opt = document.createElement("option");
        opt.value = d; opt.text = d;
        dist.add(opt);
    });
}
window.onload = function() {
    var savedDistrict = "<?php echo $district ?? ''; ?>";
    if(document.getElementById("division").value !== "") {
        loadDistricts();
        document.getElementById("district").value = savedDistrict;
    }
};
</script>
</body>
</html>