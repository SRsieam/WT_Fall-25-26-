<!DOCTYPE html>
<html>
<head>
    <title>Reset Password | Crime Detection</title>
    <link rel="stylesheet" type="text/css" href="../css/forgot_password_style.css">
</head>
<body>

<div class="reset-box">
    <h2>Account Recovery</h2>
    
    <?php if($msg): ?><div class="msg"><?php echo $msg; ?></div><?php endif; ?>

    <?php if($step == 1): ?>
        <form method="POST" action="forgot_password_controller.php">
            <label>Registered Email</label>
            <input type="email" name="email" placeholder="Enter your email" required>
            
            <label>Security Question</label>
            <select name="security_question" required>
                <option value="">-- Select Question --</option>
                <option value="What is your pet's name?">What is your pet's name?</option>
                <option value="What is your mother's maiden name?">What is your mother's maiden name?</option>
                <option value="What was your first school?">What was your first school?</option>
                <option value="What is your favorite city?">What is your favorite city?</option>
            </select>
            
            <label>Security Answer</label>
            <input type="text" name="security_answer" placeholder="Enter your answer" required autocomplete="off">
            
            <button type="submit" name="verify_btn" class="btn">Verify Identity</button>
        </form>
    <?php else: ?>
        <form method="POST" action="forgot_password_controller.php">
            <input type="hidden" name="uid" value="<?php echo $user_id_to_reset; ?>">
            <label>New Password</label>
            <input type="password" name="new_password" placeholder="New Password" required>
            
            <label>Confirm Password</label>
            <input type="password" name="confirm_new_password" placeholder="Repeat Password" required>
            
            <button type="submit" name="reset_btn" class="btn">Reset Password</button>
        </form>
    <?php endif; ?>
    
    <div class="footer-link">
        <a href="login_controller.php">← Back to Login</a>
    </div>
</div>

</body>
</html>