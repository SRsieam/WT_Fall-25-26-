<?php
include "db.php";

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {

    $content = $_POST["content"] ?? "";
    $imageName = "";

    if (!empty($_FILES["image"]["name"])) 
    {
        $imageName = time() . "_" . $_FILES["image"]["name"];
        move_uploaded_file($_FILES["image"]["tmp_name"], "uploads/" . $imageName);
    }

    if (empty($content) && empty($imageName)) 
        
    {
        $msg = "Please write something or upload an image";
    } else 
    {
        $sql = "INSERT INTO posts (content, image) VALUES ('$content', '$imageName')";
        mysqli_query($conn, $sql);
    }
}

$posts = mysqli_query($conn, "SELECT * FROM posts ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Home</title>
<style>
body {
    font-family: Arial;
    background: #f0f2f5;
}
.container {
    width: 500px;
    margin: auto;
}
.post-box {
    background: #fff;
    padding: 15px;
    border-radius: 8px;
    margin-top: 20px;
}
textarea {
    width: 100%;
    padding: 10px;
    resize: none;
}
button {
    padding: 8px 15px;
    background: #1877f2;
    color: white;
    border: none;
    margin-top: 10px;
}
.post {
    background: #fff;
    padding: 15px;
    border-radius: 8px;
    margin-top: 15px;
}
.post img {
    width: 100%;
    margin-top: 10px;
    border-radius: 8px;
}
.msg {
    color: red;
    text-align: center;
}
</style>
</head>

<body>

<div class="container">

    <div class="post-box">
        <p class="msg"><?php echo $msg; ?></p>

        <form method="post" enctype="multipart/form-data">
            <textarea name="content" rows="3" placeholder="Share your concern..."></textarea>
            <input type="file" name="image"><br>
            <button type="submit">Post</button>
        </form>
    </div>

    <?php while ($row = mysqli_fetch_assoc($posts)) 
        { ?>
        <div class="post">
            <p><?php echo nl2br($row["content"]); ?></p>

            <?php if (!empty($row["image"])) 
                { ?>
                <img src="uploads/<?php echo $row["image"]; ?>">
            <?php } ?>

            <small><?php echo $row["created_at"]; ?></small>
        </div>
    <?php } ?>

</div>

</body>
</html>
