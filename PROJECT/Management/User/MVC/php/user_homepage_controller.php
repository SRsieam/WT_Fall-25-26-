<?php
session_start();
// Pointing to the central connection model
include "../db/db_connection.php"; 

$msg = "";
$user_id = $_SESSION["user_id"] ?? 0;
$user_name = $_SESSION["user_name"] ?? "";

// Authentication Check
if ($user_id == 0) {
    header("Location: user_login_controller.php");
    exit();
}

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : "";
$filter_cat = isset($_GET['filter_cat']) ? mysqli_real_escape_string($conn, $_GET['filter_cat']) : "";
$filter_date = isset($_GET['filter_date']) ? mysqli_real_escape_string($conn, $_GET['filter_date']) : "";

$cat_query = "SELECT * FROM categories ORDER BY category_name ASC";
$categories_result = mysqli_query($conn, $cat_query);

// Logic remains same to same for crime reporting
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_post'])) {
    $content = mysqli_real_escape_string($conn, trim($_POST["content"]));
    $cat_id = mysqli_real_escape_string($conn, $_POST["category_id"] ?? "");
    $lat = mysqli_real_escape_string($conn, $_POST["lat"] ?? "");
    $lng = mysqli_real_escape_string($conn, $_POST["lng"] ?? "");
    $imageName = "";

   if (!is_dir("../uploads")) { 
    mkdir("../uploads", 0777, true); 
}

if (!empty($_FILES["image"]["name"])) {
    $imageName = time() . "_" . str_replace(" ", "_", basename($_FILES["image"]["name"]));
    
    // Path updated to move up from php/ into uploads/
    $targetPath = "../uploads/" . $imageName;
    
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetPath)) {
        // Success: File saved in PROJECT/Management/User/MVC/uploads/
    }
}

    if ($content != "" && !empty($cat_id) && !empty($lat)) {
        $sql = "INSERT INTO posts (user_id, content, category_id, image, lat, lng, status) 
                VALUES ('$user_id', '$content', '$cat_id', '$imageName', '$lat', '$lng', 'Pending')";
        
        if (mysqli_query($conn, $sql)) {
            // Nearby user notification logic (2.0km radius)
            $radius = 2.0; 
            $nearby_sql = "SELECT id FROM users WHERE id != '$user_id' AND 
                (6371 * acos(cos(radians($lat)) * cos(radians(user_lat)) * cos(radians(user_lng) - radians($lng)) + sin(radians($lat)) * sin(radians(user_lat)))) < $radius";
            
            $notif_res = mysqli_query($conn, $nearby_sql);
            if($notif_res){
                while($row_n = mysqli_fetch_assoc($notif_res)) {
                    $n_id = $row_n['id'];
                    $n_msg = "⚠️ Alert: A crime was reported near your location!";
                    mysqli_query($conn, "INSERT INTO notifications (user_id, message) VALUES ('$n_id', '$n_msg')");
                }
            }
            header("Location: user_homepage_controller.php"); 
            exit();
        } else { $msg = "Database Error: " . mysqli_error($conn); }
    } else { 
        $msg = "Please provide description, category, and Pin the Location on the map."; 
    }
}

// Fetch posts for the dashboard
$sql_fetch = "SELECT posts.*, users.name, categories.category_name 
              FROM posts 
              LEFT JOIN users ON posts.user_id = users.id 
              LEFT JOIN categories ON posts.category_id = categories.id
              WHERE 1=1"; 

if ($search != "") { $sql_fetch .= " AND (posts.content LIKE '%$search%' OR users.name LIKE '%$search%')"; }
if ($filter_cat != "") { $sql_fetch .= " AND posts.category_id = '$filter_cat'"; }
if ($filter_date != "") { $sql_fetch .= " AND DATE(posts.created_at) = '$filter_date'"; }

$sql_fetch .= " ORDER BY posts.id DESC";
$posts = mysqli_query($conn, $sql_fetch);

// Load View
include "../html/user_homepage_view.php";
?>