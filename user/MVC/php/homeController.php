<?php
session_start();
include "../db/db.php";

$msg = "";

$user_id   = $_SESSION["user_id"] ?? 0;
$user_name = $_SESSION["user_name"] ?? "";
$posts = mysqli_query(...);

if ($user_id == 0) {
    header("Location: ../html/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {

    $content   = trim($_POST["content"] ?? "");
    $imageName = "";

    $uploadDir = "../uploads/";

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (!empty($_FILES["image"]["name"])) {

        $imageName = time() . "_" . str_replace(" ", "_", basename($_FILES["image"]["name"]));
        $uploadPath = $uploadDir . $imageName;

        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $uploadPath)) {
            $msg = "Image upload failed!";
            $imageName = "";
        }
    }

    if ($content == "" && $imageName == "") {
        $msg = "What's on your mind?";
    } 
    else {
        $stmt = $conn->prepare(
            "INSERT INTO posts (user_id, content, image) VALUES (?, ?, ?)"
        );
        $stmt->bind_param("iss", $user_id, $content, $imageName);
        $stmt->execute();
        $stmt->close();
    }
}

$posts = mysqli_query(
    $conn,
    "SELECT posts.content, posts.image, posts.created_at, users.name
     FROM posts
     LEFT JOIN users ON posts.user_id = users.id
     ORDER BY posts.id DESC"
);

include "../html/homepage.php";
