<!DOCTYPE html>
<html>
<head>
    <title>Verify Email | Crime Detection</title>
    <link rel="stylesheet" type="text/css" href="../css/registration_style.css">
</head>
<body>
<div class="form-box">
    <h2>VERIFY EMAIL</h2>

    <?php if(!empty($msg)): ?>
        <div class="status-msg error" style="color: #721c24; background-color: #f8d7da; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center;">
            <?php echo $msg; ?>
        </div>
    <?php endif; ?>

    <p style="text-align:center; margin-bottom: 20px;">Please enter the 6-digit code sent to your Gmail.</p>

    <form method="post" action="../php/register_verify_controller.php">
        <div class="input-group">
            <input type="number" name="user_code" placeholder="0 0 0 0 0 0" required 
                   style="text-align:center; font-size:20px; letter-spacing:10px;">
        </div>
        <button type="submit" name="verify_reg">Complete Registration</button>
    </form>
</div>
</body>
</html>