<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <style>
        body {
            font-family: Arial;
            background: #f2f2f2;
            padding: 40px;
        }

        .profile-box {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 6px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        .box {
            margin-bottom: 12px;
            font-size: 16px;
        }

        .label {
            font-weight: bold;
            color: #229;
        }

        .edit-btn {
            margin-top: 20px;
            padding: 10px;
            width: 100%;
            background: #2196F3;
            color: white;
            border: none;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
        }

        .edit-btn:hover {
            background: #1976D2;
        }
    </style>
</head>

<body>

<div class="profile-box">
    <h2>My Profile</h2>

    <div class="box">
        <span class="label">Name:</span> Sieam
    </div>

    <div class="box">
        <span class="label">Username:</span> srs
    </div>

    <div class="box">
        <span class="label">Email:</span> srs@gmail.com
    </div>

    <div class="box">
        <span class="label">Phone:</span> 01584619846
    </div>

    <div class="box">
        <span class="label">Address:</span> Dhaka
    </div>

    <button class="edit-btn">Edit Profile</button>
</div>

</body>
</html>