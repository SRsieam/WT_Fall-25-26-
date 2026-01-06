<?php
session_start();
include "db.php"; // This must use mysqli_connect() as discussed

$msg = "";

// Check if user is logged in
$user_id = $_SESSION["user_id"] ?? 0;
$user_name = $_SESSION["user_name"] ?? "";

if ($user_id == 0) {
    die("Please login first.");
}

// 1. Handle Form Submission (Procedural Style)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitizing input for Procedural SQL
    $content = mysqli_real_escape_string($conn, trim($_POST["content"]));
    $imageName = "";

    // Ensure uploads directory exists
    if (!is_dir("uploads")) {
        mkdir("uploads", 0777, true);
    }

    // Handle Image Upload
    if (!empty($_FILES["image"]["name"])) {
        // Unique filename to prevent overwriting
        $imageName = time() . "_" . str_replace(" ", "_", basename($_FILES["image"]["name"]));
        $uploadPath = "uploads/" . $imageName;

        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $uploadPath)) {
            $msg = "Image upload failed!";
            $imageName = "";
        }
    }

    // Validation
    if ($content == "" && $imageName == "") {
        $msg = "Please provide some content or an image.";
    } else {
        // Procedural SQL Insertion
        $sql = "INSERT INTO posts (user_id, content, image) VALUES ('$user_id', '$content', '$imageName')";
        
        if (mysqli_query($conn, $sql)) {
            $msg = "Post shared successfully!";
            // Refresh to show new post
            header("Location: homepage.php"); 
            exit();
        } else {
            $msg = "Database Error: " . mysqli_error($conn);
        }
    }
}

// 2. Fetch All Posts (Procedural SQL with JOIN)
$sql_fetch = "SELECT posts.content, posts.image, posts.created_at, users.name 
              FROM posts 
              LEFT JOIN users ON posts.user_id = users.id 
              ORDER BY posts.id DESC";

$posts = mysqli_query($conn, $sql_fetch);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Home - Crime Detection</title>
    <style>
        /* Custom CSS - No Bootstrap */
        body { font-family: Arial, sans-serif; background: #f0f2f5; margin: 0; padding: 20px; }
        .container { width: 600px; margin: auto; }
        
        /* Navigation/Header */
        .header { 
            background: #fff; padding: 15px; border-radius: 8px; 
            display: flex; justify-content: space-between; align-items: center; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        /* Post Creation Box */
        .post-box { 
            background: #fff; padding: 20px; border-radius: 8px; 
            margin-top: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); 
        }
        textarea { 
            width: 100%; padding: 10px; border: 1px solid #ddd; 
            border-radius: 5px; resize: none; box-sizing: border-box; 
        }
        .btn-post { 
            background: #1877f2; color: white; border: none; 
            padding: 10px 20px; cursor: pointer; border-radius: 5px; margin-top: 10px; 
        }
        .btn-logout { 
            background: #d32f2f; color: white; text-decoration: none; 
            padding: 8px 15px; border-radius: 5px; font-size: 14px; 
        }

        /* Feed Styling */
        .post { 
            background: #fff; padding: 20px; border-radius: 8px; 
            margin-top: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); 
        }
        .post-author { font-weight: bold; color: #1c1e21; font-size: 16px; }
        .post-date { color: #65676b; font-size: 12px; margin-bottom: 10px; }
        .post-image { width: 100%; border-radius: 8px; margin-top: 10px; }
        .msg-error { color: red; text-align: center; }
    </style>
</head>
<body>

<div class="container">

    <div class="header">
        <div>Logged in as: <strong><?php echo htmlspecialchars($user_name); ?></strong></div>
        <a href="logout.php" class="btn-logout">Logout</a>
    </div>

    <div class="post-box">
        <h3>Report an Incident</h3>
        <p class="msg-error"><?php echo $msg; ?></p>
        <form method="post" enctype="multipart/form-data">
            <textarea name="content" rows="3" placeholder="Describe the incident..."></textarea>
            <br><br>
            <label>Attach Evidence (Image):</label><br>
            <input type="file" name="image">
            <br>
            <button type="submit" class="btn-post">Submit Report</button>
        </form>
    </div>

    <h3 style="margin-top: 30px;">Recent Crime Reports</h3>

    

    <?php if (mysqli_num_rows($posts) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($posts)): ?>
            <div class="post">
                <div class="post-author"><?php echo htmlspecialchars($row["name"]); ?></div>
                <div class="post-date"><?php echo $row["created_at"]; ?></div>
                
                <p><?php echo nl2br(htmlspecialchars($row["content"])); ?></p>

                <?php if (!empty($row["image"])): ?>
                    <img src="uploads/<?php echo htmlspecialchars($row["image"]); ?>" class="post-image">
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align:center; color: gray;">No reports found in your area.</p>
    <?php endif; ?>

</div>

</body>
</html>