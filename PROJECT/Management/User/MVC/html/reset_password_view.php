<!DOCTYPE html>
<html>
<head>
    <title>New Password</title>
    <link rel="stylesheet" href="../css/forgot_password_style.css">
</head>
<body>
    <div class="reset-box">
        <h2>Create New Password</h2>
        <?php if($msg) echo "<div class='msg'>$msg</div>"; ?>
        <form method="POST" action="../php/reset_password_controller.php">
            <label>New Password</label>
            <input type="password" name="n_pass" required>
            <label>Confirm Password</label>
            <input type="password" name="c_pass" required>
            <button type="submit" name="update_pass_btn" class="btn">Update Password</button>
        </form>
    </div>
</body>
</html>