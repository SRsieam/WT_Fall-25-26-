<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link rel="stylesheet" href="../css/forgot_password_style.css">
</head>
<body>
    <div class="reset-box">
        <h2>Reset Password</h2>
        <?php if($msg) echo "<p class='msg'>$msg</p>"; ?>
        <form method="POST" action="../php/forgot_password_controller.php">
            <label>Enter Registered Email</label>
            <input type="email" name="email" required>
            <button type="submit" name="send_code" class="btn">Send Verification Code</button>
        </form>
        <div class="footer-link"><a href="login_controller.php">Back to Login</a></div>
    </div>
</body>
</html>