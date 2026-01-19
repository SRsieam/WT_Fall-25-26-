<?php
include "db.php";

// Set charset to handle special characters like apostrophes correctly
mysqli_set_charset($conn, "utf8mb4");

$msg = "";
$step = 1; 
$user_id_to_reset = 0;

// STEP 1: Verify Identity
if (isset($_POST['verify_btn'])) {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $selected_question = trim($_POST['security_question']); // Question from dropdown
    $user_answer = trim($_POST['security_answer']);

    $sql = "SELECT id, security_question, security_answer FROM users WHERE email='$email'";
    $res = mysqli_query($conn, $sql);

    if ($row = mysqli_fetch_assoc($res)) {
        // Retrieve and clean database strings
        $db_question = trim($row['security_question']);
        $db_answer = trim($row['security_answer']);

        // LOGIC FIX: Case-insensitive comparison without hidden spaces
        if (strcasecmp($db_question, $selected_question) !== 0) {
            $msg = "The security question selected does not match our records.";
        } 
        // Verify hashed answer
        else if (password_verify($user_answer, $db_answer)) {
            $step = 2;
            $user_id_to_reset = $row['id'];
        } else {
            $msg = "Incorrect security answer.";
        }
    } else {
        $msg = "No account found with that email address.";
    }
}

// STEP 2: Update Password
if (isset($_POST['reset_btn'])) {
    $uid = mysqli_real_escape_string($conn, $_POST['uid']);
    $new_pass = $_POST['new_password'];
    $c_new_pass = $_POST['confirm_new_password'];

    if (strlen($new_pass) < 6 || !preg_match("/[@$!%*#?&]/", $new_pass)) {
        $msg = "Password must be at least 6 characters with a special symbol.";
        $step = 2;
        $user_id_to_reset = $uid;
    } else if ($new_pass !== $c_new_pass) {
        $msg = "Passwords do not match.";
        $step = 2;
        $user_id_to_reset = $uid;
    } else {
        $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
        if (mysqli_query($conn, "UPDATE users SET password='$hashed_pass' WHERE id='$uid'")) {
            // Redirect using the file name you established
            header("Location: loginuser.php?msg=Password reset successful!");
            exit();
        } else {
            $msg = "Database error. Could not reset password.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password | Crime Detection</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f7f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .reset-box { background: white; padding: 30px; width: 400px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-top: 5px solid #e74c3c; }
        h2 { text-align: center; color: #2c3e50; margin-bottom: 20px; }
        input, select { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box; font-size: 14px; }
        .btn { width: 100%; padding: 12px; background: #e74c3c; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; transition: 0.3s; }
        .btn:hover { background: #c0392b; }
        .msg { padding: 10px; color: #af2b2b; background: #fdecea; border-radius: 5px; margin-bottom: 15px; font-size: 13px; text-align: center; border: 1px solid #f5c6cb; }
        label { font-size: 12px; color: #777; font-weight: bold; margin-left: 2px; }
    </style>
</head>
<body>

<div class="reset-box">
    <h2>Account Recovery</h2>
    
    <?php if($msg): ?><div class="msg"><?php echo $msg; ?></div><?php endif; ?>

    <?php if($step == 1): ?>
        <form method="POST">
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
        <form method="POST">
            <input type="hidden" name="uid" value="<?php echo $user_id_to_reset; ?>">
            <label>New Password</label>
            <input type="password" name="new_password" placeholder="New Password" required>
            
            <label>Confirm Password</label>
            <input type="password" name="confirm_new_password" placeholder="Repeat Password" required>
            
            <button type="submit" name="reset_btn" class="btn">Reset Password</button>
        </form>
    <?php endif; ?>
    
    <div style="text-align:center; margin-top:20px; font-size: 13px;">
        <a href="loginuser.php" style="text-decoration:none; color:#3498db; font-weight:bold;">← Back to Login</a>
    </div>
</div>

</body>
</html>