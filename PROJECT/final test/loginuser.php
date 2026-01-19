<?php
session_start();
include "db.php";

$msg = "";
$email = "";

function text_input($data)
{
    return trim($data);
}

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    // Secure input
    $email = mysqli_real_escape_string($conn, text_input($_POST["email"]));
    $password = text_input($_POST["password"]);

    if (empty($email) || empty($password)) {
        $msg = "Please enter email and password";
    } 
    else 
    {
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);

        if ($row = mysqli_fetch_assoc($result)) 
        {
            if (password_verify($password, $row["password"])) 
            {
                // 🔒 Ban / Suspension Check
                if ($row['role'] == 'Banned') {
                    $msg = "🚫 Access Denied: Your account is permanently banned.";
                } 
                elseif ($row['role'] == 'Suspended') {
                    $msg = "⚠️ Account Suspended: Please contact support.";
                } 
                else 
                {
                    // ✅ Login Success
                    $_SESSION["user_id"] = $row["id"];
                    $_SESSION["user_name"] = $row["name"];
                    $_SESSION["is_user_logged_in"] = true;

                    // ⏰ Update Last Login
                    $user_id = $row["id"];
                    mysqli_query($conn, "UPDATE users SET last_login = NOW() WHERE id = $user_id");

                    header("Location: homepage.php");
                    exit();
                }
            } 
            else {
                $msg = "Incorrect password";
            }
        } 
        else {
            $msg = "Email not found";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login | Crime Detection System</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(rgba(0,0,0,.5), rgba(0,0,0,.5)), url('loginbg.png');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .login-card {
            background: rgba(255,255,255,.95);
            padding: 40px;
            width: 350px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,.4);
            text-align: center;
        }

        h2 { margin-bottom: 25px; }

        .input-group { margin-bottom: 20px; text-align: left; }

        label { font-weight: 600; font-size: 14px; }

        input {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: #e74c3c;
            border: none;
            color: #fff;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-login:hover { background: #c0392b; }

        .error-box {
            background: #ffebee;
            color: #d32f2f;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            display: <?php echo empty($msg) ? 'none' : 'block'; ?>;
        }

        .footer-links { margin-top: 20px; font-size: 14px; }

        .footer-links a {
            color: #e74c3c;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="login-card">
    <h2>LOGIN</h2>

    <div class="error-box"><?php echo $msg; ?></div>

    <form method="post" autocomplete="off">
        <div class="input-group">
            <label>Email</label>
            <input type="email" name="email"
                   value="<?php echo htmlspecialchars($email); ?>"
                   required>
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <button type="submit" class="btn-login">Login</button>
    </form>

    <div style="margin-top:15px;">
        <a href="forgot_password.php">Forgot Password?</a>
    </div>

    <div class="footer-links">
        Don't have an account? <br>
        <a href="registrationuser.php">Create Account</a>
    </div>
</div>

</body>
</html>
