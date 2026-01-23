<!DOCTYPE html>
<html>
<head>
    <title>Login | Crime Detection System</title>
    <link rel="stylesheet" type="text/css" href="../css/login_style.css">
</head>
<body>

<div class="login-card">
    <h2>LOGIN</h2>

    <?php if(!empty($msg)): ?>
        <div class="error-box"><?php echo $msg; ?></div>
    <?php endif; ?>

    <form method="post" action="login_controller.php" autocomplete="off">
        <div class="input-group">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <button type="submit" class="btn-login">Login</button>
    </form>

    <div style="margin-top:15px;">
        <a href="forgot_password_controller.php">Forgot Password?</a>
    </div>

    <div class="form-box">
    <h2>LOGIN</h2>

    <?php if(isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="status-msg success" style="color: #155724; background-color: #d4edda; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center;">
            Registration Successful! You can now login.
        </div>
    <?php endif; ?>

    <form method="post" action="../php/login_controller.php">
        ...
    </form>
</div>

    <div class="footer-links">
        Don't have an account? <br>
        <a href="registration_controller.php">Create Account</a>
    </div>
</div>

</body>
</html>