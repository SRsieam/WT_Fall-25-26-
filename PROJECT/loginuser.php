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
    } else 
    {
   
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
    <title>Login - Real Time Crime Detection</title>
    <style>

        body { 
            font-family: Arial; 
            background: #f2f2f2; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            margin: 0; 
        }
        .form-box { 
            background: #fff; 
            padding: 25px; 
            width: 320px; 
            border-radius: 8px; 
            box-shadow: 0 0 10px gray; 
        }
        h2 { text-align: center; }
        input { width: 100%; padding: 10px; margin: 10px 0; box-sizing: border-box; }
        button { 
            width: 100%; 
            padding: 12px; 
            background: green; 
            color: white; 
            border: none; 
            cursor: pointer; 
        }
        .error-msg { 
            text-align: center; 
            color: red; 
            font-weight: bold; 
        }
    </style>
</head>
<body>

<div class="form-box">
    <h2>Login</h2>
    
    <p class="error-msg"><?php echo $msg; ?></p>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        
        <input type="email" name="email" placeholder="Email" value="<?php echo $email; ?>" required>
        
        <input type="password" name="password" placeholder="Password" required>
        
        <button type="submit">Login</button>
    </form>
    
    <p style="text-align:center; margin-top:15px;">
        <a href="registrationuser.php">Create an Account</a>
    </p>
</div>

</body>
</html>