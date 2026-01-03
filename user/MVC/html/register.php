<!DOCTYPE html>
<html>
<head>
<title>Registration</title>
<link rel="stylesheet" href="../css/register.css">
</head>

<body>

<div class="form-box">
<h2>Registration</h2>

<?php if (!empty($msg)) 
    { 
    ?>
<p class="<?php echo $success ? 'success' : 'error'; ?>">
    <?php echo $msg; ?>
</p>
<?php } ?>

<form method="post" action="../php/registerController.php" autocomplete="off">

    <input type="text" name="name" placeholder="Full Name" required>
    <input type="text" name="email" placeholder="Email" required>

    <select name="division" id="division" onchange="loadDistricts()" required>
        <option value="">Select Division</option>
        <option>Dhaka</option>
        <option>Chattogram</option>
        <option>Rajshahi</option>
        <option>Khulna</option>
        <option>Barishal</option>
        <option>Sylhet</option>
        <option>Rangpur</option>
        <option>Mymensingh</option>
    </select>

    <select name="district" id="district" required>
        <option value="">Select District</option>
    </select>

    <input type="date" name="dob" required>
    <input type="text" name="phone" placeholder="Phone Number" required>

    <input type="password" name="password" placeholder="Password" required>
    <input type="password" name="cpassword" placeholder="Confirm Password" required>

    <button type="submit">Register</button><br><br>

    <a href="loginController.php"><button type="button">Login</button></a>
</form>
</div>

<script src="../js/district.js"></script>
</body>
</html>
