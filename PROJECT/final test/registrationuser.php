<?php
include "db.php";

$msg = "";
$success = false;

$name = $email = $division = $district = $dob = $phone = $security_question = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name      = mysqli_real_escape_string($conn, trim($_POST["name"] ?? ""));
    $email     = mysqli_real_escape_string($conn, trim($_POST["email"] ?? ""));
    $division  = mysqli_real_escape_string($conn, trim($_POST["division"] ?? ""));
    $district  = mysqli_real_escape_string($conn, trim($_POST["district"] ?? ""));
    $dob       = mysqli_real_escape_string($conn, trim($_POST["dob"] ?? ""));
    $phone     = mysqli_real_escape_string($conn, trim($_POST["phone"] ?? ""));
    $password  = $_POST["password"] ?? "";
    $cpassword = $_POST["cpassword"] ?? "";
    
    // NEW: Capture Security Data
    $security_question = mysqli_real_escape_string($conn, $_POST["security_question"] ?? "");
    $security_answer   = trim($_POST["security_answer"] ?? "");

    // Validations
    if ($name == "" || $email == "" || $dob == "" || $phone == "" || $security_answer == "" || $security_question == "") {
        $msg = "All required fields must be filled.";
    }
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "Enter a valid email address.";
    }
    else if (strlen($password) < 6 || !preg_match("/[@$!%*#?&]/", $password)) {
        $msg = "Password must be at least 6 characters and include a special symbol.";
    }
    else if ($password !== $cpassword) {
        $msg = "Passwords do not match.";
    }
    else {
        $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
        if (mysqli_num_rows($check) > 0) {
            $msg = "This email is already registered.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            // Hash the security answer for safety
            $hash_answer = password_hash($security_answer, PASSWORD_DEFAULT);

            // UPDATED QUERY: Added security_question and security_answer
            $sql = "INSERT INTO users 
                    (name, email, division, district, dob, phone, password, security_question, security_answer)
                    VALUES 
                    ('$name', '$email', '$division', '$district', '$dob', '$phone', '$hash', '$security_question', '$hash_answer')";

            if (mysqli_query($conn, $sql)) {
                $msg = "Registration successful!";
                $success = true;
                // Clear fields on success
                $name = $email = $division = $district = $dob = $phone = "";
            } else {
                $msg = "Database error: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration | Crime Detection</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.8)), url("bg_rg.png") no-repeat center center fixed;
            background-size: cover;
            display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0;
        }
        .form-box {
            background: #ffffff;
            padding: 30px; width: 450px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.5);
            margin: 20px 0;
        }
        h2 { text-align: center; color: #2c3e50; border-bottom: 2px solid #e74c3c; padding-bottom: 10px; margin-bottom: 20px; }
        .input-group { margin-bottom: 15px; }
        input, select { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box; font-size: 14px; }
        input:focus, select:focus { border-color: #e74c3c; outline: none; }
        .status-msg { padding: 10px; text-align: center; border-radius: 5px; margin-bottom: 15px; font-weight: bold; font-size: 13px; }
        .error { background: #fdecea; color: #af2b2b; border: 1px solid #f5c6cb; }
        .success { background: #e6f4ea; color: #1e7e34; border: 1px solid #c3e6cb; }
        button { width: 100%; padding: 14px; background: #e74c3c; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; margin-top: 10px; }
        button:hover { background: #c0392b; }
        .footer { text-align: center; margin-top: 15px; font-size: 14px; }
        .footer a { color: #3498db; text-decoration: none; font-weight: bold; }
        .sec-label { font-size: 12px; color: #e74c3c; font-weight: bold; margin-bottom: 5px; display: block; }
    </style>
</head>
<body>

<div class="form-box">
    <h2>REGISTRATION</h2>

    <?php if(!empty($msg)): ?>
        <div class="status-msg <?php echo $success ? 'success' : 'error'; ?>">
            <?php echo $msg; ?>
        </div>
    <?php endif; ?>

    <form method="post" autocomplete="off">
        <div class="input-group">
            <input type="text" name="name" placeholder="Full Name" value="<?php echo htmlspecialchars($name); ?>" required>
        </div>

        <div class="input-group">
            <input type="email" name="email" placeholder="Email Address" value="<?php echo htmlspecialchars($email); ?>" required>
        </div>

        <div class="input-group" style="display: flex; gap: 10px;">
            <select name="division" id="division" onchange="loadDistricts()" required>
                <option value="">Select Division</option>
                <option <?php if($division=="Dhaka") echo "selected"; ?>>Dhaka</option>
                <option <?php if($division=="Chattogram") echo "selected"; ?>>Chattogram</option>
                <option <?php if($division=="Rajshahi") echo "selected"; ?>>Rajshahi</option>
                <option <?php if($division=="Khulna") echo "selected"; ?>>Khulna</option>
                <option <?php if($division=="Barishal") echo "selected"; ?>>Barishal</option>
                <option <?php if($division=="Sylhet") echo "selected"; ?>>Sylhet</option>
                <option <?php if($division=="Rangpur") echo "selected"; ?>>Rangpur</option>
                <option <?php if($division=="Mymensingh") echo "selected"; ?>>Mymensingh</option>
            </select>
            <select name="district" id="district" required>
                <option value="">District</option>
            </select>
        </div>

        <div class="input-group">
            <label style="font-size: 11px; color: #777; margin-left: 2px;">Date of Birth</label>
            <input type="date" name="dob" value="<?php echo $dob; ?>" required>
        </div>

        <div class="input-group">
            <input type="text" name="phone" placeholder="Phone Number (11 Digits)" value="<?php echo htmlspecialchars($phone); ?>" required>
        </div>

        <div class="input-group" style="background: #f9f9f9; padding: 10px; border-radius: 8px; border: 1px dashed #ccc;">
            <span class="sec-label">Account Recovery Settings</span>
            <select name="security_question" required style="margin-bottom: 10px;">
                <option value="">Select Security Question</option>
                <option value="What is your pet's name?">What is your pet's name?</option>
                <option value="What is your mother's maiden name?">What is your mother's maiden name?</option>
                <option value="What was your first school?">What was your first school?</option>
                <option value="What is your favorite city?">What is your favorite city?</option>
            </select>
            <input type="text" name="security_answer" placeholder="Your Answer" required>
        </div>

        <div class="input-group">
            <input type="password" name="password" placeholder="Password" required autocomplete="new-password">
        </div>

        <div class="input-group">
            <input type="password" name="cpassword" placeholder="Confirm Password" required>
        </div>

        <button type="submit">Create Account</button>
        
        <div class="footer">
            Already have an account? <a href="loginuser.php">Login</a>
        </div>
    </form>
</div>

<script>
function loadDistricts() {
    var div = document.getElementById("division").value;
    var dist = document.getElementById("district");
    dist.innerHTML = "<option value=''>Select District</option>";
    var list = [];

    if (div == "Dhaka") {
        list = ["Dhaka", "Gazipur", "Narayanganj", "Narsingdi", "Tangail", "Kishoreganj", "Manikganj", "Munshiganj", "Rajbari", "Madaripur", "Gopalganj", "Faridpur", "Shariatpur"];
    } 
    else if (div == "Chattogram") {
        list = ["Chattogram", "Cox's Bazar", "Cumilla", "Feni", "Brahmanbaria", "Noakhali", "Lakshmipur", "Chandpur", "Khagrachari", "Rangamati", "Bandarban"];
    } 
    else if (div == "Rajshahi") { list = ["Rajshahi", "Bogura", "Pabna", "Sirajganj", "Naogaon", "Natore", "Joypurhat", "Chapai Nawabganj"]; } 
    else if (div == "Khulna") { list = ["Khulna", "Jashore", "Satkhira", "Bagerhat", "Kushtia", "Magura", "Meherpur", "Narail", "Chuadanga", "Jhenaidah"]; } 
    else if (div == "Barishal") { list = ["Barishal", "Bhola", "Patuakhali", "Pirojpur", "Jhalokathi", "Barguna"]; } 
    else if (div == "Sylhet") { list = ["Sylhet", "Moulvibazar", "Habiganj", "Sunamganj"]; } 
    else if (div == "Rangpur") { list = ["Rangpur", "Dinajpur", "Kurigram", "Gaibandha", "Nilphamari", "Panchagarh", "Thakurgaon", "Lalmonirhat"]; } 
    else if (div == "Mymensingh") { list = ["Mymensingh", "Jamalpur", "Netrokona", "Sherpur"]; }

    for (var i = 0; i < list.length; i++) {
        var opt = document.createElement("option");
        opt.value = list[i];
        opt.text = list[i];
        dist.add(opt);
    }
}

window.onload = function() {
    var savedDivision = "<?php echo $division; ?>";
    if(savedDivision !== "") {
        loadDistricts();
        document.getElementById("district").value = "<?php echo $district; ?>";
    }
};
</script>

</body>
</html>