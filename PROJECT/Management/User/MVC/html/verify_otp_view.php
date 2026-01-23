<!DOCTYPE html>
<html>
<head>
    <title>Verify Code | Crime Alert</title>
    <link rel="stylesheet" href="../css/forgot_password_style.css">
</head>
<body>
    <div class="reset-box">
        <h2>Verify Gmail Code</h2>
        <p style="font-size: 13px; color: #666; text-align: center;">
            Enter the 6-digit code sent to <?php echo $_SESSION['reset_email']; ?>
        </p>

        <?php if($msg) echo "<div class='msg'>$msg</div>"; ?>

        <form method="POST" action="../php/verify_otp_controller.php">
            <input type="number" name="otp_code" placeholder="000000" required 
                   style="text-align: center; font-size: 24px; letter-spacing: 5px;">
            <button type="submit" name="verify_otp_btn" class="btn">Verify & Continue</button>
        </form>
    </div>
</body>
</html>