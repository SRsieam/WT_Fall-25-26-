<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Crime Alert</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f9;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        .welcome-card {
            background: white;
            padding: 50px;
            width: 100%;
            max-width: 450px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            text-align: center;
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 2rem;
        }

        p {
            color: #666;
            margin-bottom: 40px;
            font-size: 1.1rem;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 15px;
            margin: 15px 0;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
            transition: transform 0.2s, box-shadow 0.2s;
            box-sizing: border-box;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        /* Admin Button - Dark */
        .btn-admin {
            background-color: #343a40;
            color: white;
        }
        
        /* User Button - Blue */
        .btn-user {
            background-color: #007bff;
            color: white;
        }

        /* Sign Up Button - Green */
        .btn-new {
            background-color: #28a745;
            color: white;
        }

        .icon {
            font-size: 50px;
            margin-bottom: 20px;
            display: block;
        }
    </style>
</head>
<body>

    <div class="welcome-card">
        <span class="icon">🛡️</span>
        <h1>Crime Alert System</h1>
        <p>Welcome! Please select an option to continue.</p>

        <a href="admin_login.php" class="btn btn-admin">Admin Login</a>

        <a href="loginuser.php" class="btn btn-user">User Login</a>

        <a href="registrationuser.php" class="btn btn-new">New User Registration</a>
    </div>

</body>
</html>