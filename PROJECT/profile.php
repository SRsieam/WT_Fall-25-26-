<?php
session_start();
include "db.php"; // Database connection

// 1. Authentication Check
$user_id = $_SESSION["user_id"] ?? 0;
$user_name = $_SESSION["user_name"] ?? "";

if ($user_id == 0) { 
    header("Location: loginuser.php"); 
    exit(); 
}

$success = $error = "";
$mode = $_GET['mode'] ?? 'view';

// 2. Handle DELETE Action
if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    $sql = "DELETE FROM posts WHERE id=$id AND user_id=$user_id";
    if (mysqli_query($conn, $sql)) {
        header("Location: profile.php?mode=delete&success=deleted");
        exit();
    }
}

// 3. Handle UPDATE Action
if (isset($_POST['update'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $sql = "UPDATE posts SET content='$content' WHERE id=$id AND user_id=$user_id";
    if (mysqli_query($conn, $sql)) {
        $success = "Report updated successfully!";
    } else {
        $error = mysqli_error($conn);
    }
}

// 4. Fetch Post for Editing (Allows viewing old content)
$editPost = null;
if (isset($_GET['edit'])) {
    $id = mysqli_real_escape_string($conn, $_GET['edit']);
    $res = mysqli_query($conn, "SELECT * FROM posts WHERE id=$id AND user_id=$user_id");
    $editPost = mysqli_fetch_assoc($res);
}

// 5. Fetch Account Data & Statistics
$user_q = mysqli_query($conn, "SELECT * FROM users WHERE id = '$user_id'");
$user_data = mysqli_fetch_assoc($user_q);

$total_stats = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM posts WHERE user_id = '$user_id'"))['count'];
$pending_stats = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM posts WHERE user_id = '$user_id' AND status = 'Pending'"))['count'];
$resolved_stats = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM posts WHERE user_id = '$user_id' AND status = 'Resolved'"))['count'];

// 6. Fetch Reports List
$sql_list = "SELECT posts.*, categories.category_name 
             FROM posts 
             LEFT JOIN categories ON posts.category_id = categories.id 
             WHERE user_id = '$user_id' ORDER BY posts.id DESC";
$reports = mysqli_query($conn, $sql_list);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile | Crime Detection</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f7f6; margin: 0; display: flex; }
        .sidebar { width: 250px; background: #1a252f; color: white; height: 100vh; position: fixed; padding: 25px; box-sizing: border-box; z-index: 1001; }
        .sidebar h2 { color: #e74c3c; margin-top: 0; font-size: 1.2rem; border-bottom: 1px solid #34495e; padding-bottom: 10px; }
        .nav-btn { display: block; background: #34495e; color: white; text-decoration: none; padding: 12px; margin-top: 10px; border-radius: 6px; font-size: 14px; text-align: center; transition: 0.3s; }
        .active-btn { background: #e74c3c; }
        .main { margin-left: 250px; padding: 40px; width: 100%; box-sizing: border-box; }
        .card { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 30px; }
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 20px; border-radius: 12px; text-align: center; border-bottom: 4px solid #ddd; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 12px; overflow: hidden; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        .status-badge { padding: 4px 8px; border-radius: 4px; font-size: 10px; font-weight: bold; }
        .Pending { background: #fff3cd; color: #856404; }
        .Resolved { background: #d4edda; color: #155724; }
        textarea { width: 100%; padding: 12px; border: 1px solid #3498db; border-radius: 6px; font-family: inherit; margin-top: 10px; box-sizing: border-box; }
        .btn-save { background: #2ecc71; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>CRIME REPORT</h2>
    <a href="homepage.php" class="nav-btn">Dashboard</a>
    <a href="profile.php" class="nav-btn <?php echo ($mode == 'view' && !$editPost) ? 'active-btn' : ''; ?>">My Profile</a>
    <a href="update_profile.php" class="nav-btn">⚙️ Edit Profile</a>
    <a href="profile.php?mode=delete" class="nav-btn <?php echo ($mode == 'delete') ? 'active-btn' : ''; ?>" style="background:#c0392b;">Delete Reports</a>
    <a href="logout.php" class="nav-btn" style="margin-top:40px; background:#c0392b;">Logout</a>
</div>

<div class="main">
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h3>Account Information</h3>
            <a href="update_profile.php" style="color:#3498db; text-decoration:none; font-size:13px; font-weight:bold;">Edit Info ⚙️</a>
        </div>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($user_name); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user_data['email'] ?? 'N/A'); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($user_data['phone'] ?? 'N/A'); ?></p>
    </div>

    <?php if ($editPost): ?>
    <div class="card" style="border: 2px solid #3498db; background: #ebf5fb;">
        <h3 style="margin-top:0;">Update Report #<?php echo $editPost['id']; ?></h3>
        <form method="post" action="profile.php">
            <input type="hidden" name="id" value="<?php echo $editPost['id']; ?>">
            <label style="font-size:13px; font-weight:bold; color:#2c3e50;">Current Message Content:</label>
            <textarea name="content" rows="4"><?php echo htmlspecialchars($editPost['content']); ?></textarea>
            <div style="margin-top:15px;">
                <button type="submit" name="update" class="btn-save">Save Changes</button>
                <a href="profile.php" style="margin-left: 15px; color:#e74c3c; text-decoration:none; font-weight:bold;">Cancel</a>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <div class="stats-grid">
        <div class="stat-card" style="border-color: #3498db;"><p>Total</p><h1><?php echo $total_stats; ?></h1></div>
        <div class="stat-card" style="border-color: #f1c40f;"><p>Pending</p><h1><?php echo $pending_stats; ?></h1></div>
        <div class="stat-card" style="border-color: #2ecc71;"><p>Resolved</p><h1><?php echo $resolved_stats; ?></h1></div>
    </div>

    <?php if($success): ?><p style="color:green; font-weight:bold;"><?php echo $success; ?></p><?php endif; ?>

    <div class="card <?php echo ($mode == 'delete') ? 'delete-highlight' : ''; ?>">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Category</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if($reports && mysqli_num_rows($reports) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($reports)): ?>
                    <tr>
                        <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                        <td><strong><?php echo htmlspecialchars($row['category_name'] ?? 'General'); ?></strong></td>
                        <td style="max-width:250px; font-size:13px; color:#555;"><?php echo htmlspecialchars($row['content']); ?></td>
                        <td><span class="status-badge <?php echo $row['status']; ?>"><?php echo $row['status']; ?></span></td>
                        <td>
                            <?php if ($mode == 'delete'): ?>
                                <a href="profile.php?delete=<?php echo $row['id']; ?>&mode=delete" style="color:#e74c3c; font-weight:bold; text-decoration:none;" onclick="return confirm('Permanently delete this report?')">Confirm Delete</a>
                            <?php else: ?>
                                <a href="profile.php?edit=<?php echo $row['id']; ?>" style="color:#3498db; font-weight:bold; text-decoration:none;">Edit</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5" style="text-align:center; padding:30px; color:#999;">No personal reports found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>