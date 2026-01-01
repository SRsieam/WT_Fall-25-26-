<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    <style>
        body {
            font-family: Arial;
            background: #2a6388ff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-box {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            width: 300px;
        }

        .login-box h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background: #45a049;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }

        .forgot {
            text-align: center;
            margin-top: 10px;
        }

        .forgot a {
            text-decoration: none;
            color: #4CAF50;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Login</h2>

    <?php
    $username = "";
    $password = "";
    $usererror = "";
    $passerror = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (empty($_POST["username"])) {
            $usererror = "Username is required";
        } else {
            $username = test_input($_POST["username"]);
            if (!preg_match("/^[a-zA-Z0-9._-]{3,20}$/", $username)) {
                $usererror = "Invalid username format";
            }
        }

        if (empty($_POST["password"])) {
            $passerror = "Password is required";
        } else {
            $password = test_input($_POST["password"]);
            if (strlen($password) < 6) {
                $passerror = "Password must be at least 6 characters long";
            }
        }
    }

    <form>
        <input type="text" name="username" placeholder="Username or Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>

</div>

</body>
</html>