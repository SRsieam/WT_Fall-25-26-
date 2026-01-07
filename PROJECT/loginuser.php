<?php
session_start();
include "db.php"; 

$msg = "";
$email = "";

function text_input($data) {
    return trim($data);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = text_input($_POST["email"]);
    $password = text_input($_POST["password"]);

    if (empty($email) || empty($password)) {
        $msg = "Please enter email and password";
    } else {
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);

        if ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $row["password"])) {
                $_SESSION["user_id"] = $row["id"];
                $_SESSION["user_name"] = $row["name"];
                header("Location: homepage.php");
                exit();
            } else {
                $msg = "Incorrect password";
            }
        } else {
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

            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('loginbg.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-card {

            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            width: 350px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.4);
            text-align: center;
            backdrop-filter: blur(5px);
        }

        h2 { 
            color: #333; 
            margin-bottom: 25px; 
            font-weight: 600;
            letter-spacing: 1px;
        }

        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-size: 14px;
            font-weight: 600;
        }

        input { 
            width: 100%; 
            padding: 12px; 
            border: 1px solid #ccc; 
            border-radius: 8px; 
            box-sizing: border-box;
            transition: all 0.3s;
            font-size: 15px;
            background: rgba(255, 255, 255, 0.8);
        }

        input:focus {
            outline: none;
            border-color: #e74c3c;
            box-shadow: 0 0 8px rgba(231, 76, 60, 0.2);
        }

        .btn-login { 
            width: 100%; 
            padding: 12px; 
            background: #e74c3c; 
            color: white; 
            border: none; 
            border-radius: 8px;
            cursor: pointer; 
            font-size: 16px;
            font-weight: bold;
            transition: background 0.3s;
            margin-top: 10px;
            text-transform: uppercase;
        }

        .btn-login:hover {
            background: #c0392b;
        }

        .error-box {
            color: #d32f2f;
            background: #ffebee;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 13px;
            border: 1px solid #ffcdd2;
            display: <?php echo empty($msg) ? 'none' : 'block'; ?>;
        }

        .footer-links {
            margin-top: 25px;
            font-size: 14px;
            color: #666;
        }

        .footer-links a {
            color: #e74c3c;
            text-decoration: none;
            font-weight: bold;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-card">
    <h2>LOGIN</h2>
    
    <div class="error-box"><?php echo $msg; ?></div>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" autocomplete="off">
        
        <div class="input-group">
            <label>Email Address</label>
            <input type="email" name="email" value="<?php echo $email; ?>" placeholder="Enter your email" required>
        </div>
        
        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" value="" placeholder="Enter your password" autocomplete="new-password" required>
        </div>
        
        <button type="submit" class="btn-login">Login</button>
    </form>
    
    <div class="footer-links">
        Don't have an account? <br>
        <a href="registrationuser.php">Create an Account</a>
    </div>
</div>

</body>
</html>