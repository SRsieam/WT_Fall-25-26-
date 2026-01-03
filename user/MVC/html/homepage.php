<?php
session_start();
include "../db/db.php";

$user_name = $_SESSION["user_name"] ?? "Guest";
$posts = mysqli_query(
    $conn,
    "SELECT posts.content, posts.image, posts.created_at, users.name
     FROM posts
     LEFT JOIN users ON posts.user_id = users.id
     ORDER BY posts.id DESC"
);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <link rel="stylesheet" href="../css/home.css">
</head>
<body>

<div class="container">

    <div class="post-box">
        <div class="top-bar">
            <p>
                <strong>Logged in as:</strong>
                <?php echo htmlspecialchars($user_name); ?>
            </p>

            <form action="../php/logout.php" method="post">
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>

        <?php if (!empty($msg)) { ?>
            <p class="msg"><?php echo $msg; ?></p>
        <?php } ?>

        <form method="post" action="../php/homeController.php" enctype="multipart/form-data">
            <textarea name="content" rows="3" placeholder="Share your concern..."></textarea>
            <input type="file" name="image"><br>
            <button type="submit">Post</button>
        </form>
    </div>

    <?php if (mysqli_num_rows($posts) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($posts)): ?>
            <div class="post">
                <div class="post-name">
                    <?php echo htmlspecialchars($row["name"] ?? "Unknown User"); ?>
                </div>

                <p><?php echo nl2br(htmlspecialchars($row["content"])); ?></p>

                <?php if (!empty($row["image"])): ?>
                    <img src="../uploads/<?php echo htmlspecialchars($row["image"]); ?>">
                <?php endif; ?>

                <small><?php echo $row["created_at"]; ?></small>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="no-post">No posts yet.</p>
    <?php endif; ?>

</div>

</body>
</html>
