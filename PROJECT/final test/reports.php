<?php
session_start();
include 'db.php'; 

if (!isset($_SESSION['is_admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

$msg = "";

if (isset($_POST['approve_report'])) {
    $id = intval($_POST['report_id']);
    $sql = "UPDATE posts SET status='Resolved' WHERE id=$id";
    if (mysqli_query($conn, $sql)) {
        $msg = "<div class='alert success'>Report #$id marked as Resolved</div>";
    }
}

if (isset($_POST['delete_report'])) {
    $id = intval($_POST['report_id']);
    $sql = "DELETE FROM posts WHERE id=$id";
    if (mysqli_query($conn, $sql)) {
        $msg = "<div class='alert danger'>Report #$id has been deleted</div>";
    }
}

$sql = "SELECT p.*, u.name as reporter_name, u.email as reporter_email, c.category_name 
        FROM posts p 
        JOIN users u ON p.user_id = u.id 
        LEFT JOIN categories c ON p.category_id = c.id
        ORDER BY p.created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<head>
    <title>Manage All Reports</title>
    <style>
        body { margin: 0; font-family: 'Segoe UI', sans-serif; background: #f4f6f9; display: flex; height: 100vh; }
        
        .sidebar { 
            width: 250px; 
            background: #343a40; 
            color: white; 
            display: flex; 
            flex-direction: column; 
            flex-shrink: 0; }
        .sidebar-header { 
            padding: 20px; 
            font-weight: bold; 
            background: #212529; 
            text-align: center; 
            font-size: 1.2rem; }
        .sidebar a { 
            display: block; 
            padding: 15px 20px; 
            color: #c2c7d0; 
            text-decoration: none; 
            border-bottom: 1px solid #3f474e; 
            transition: 0.3s; }
        .sidebar a:hover { 
            background: #007bff; 
            color: white; 
            padding-left: 25px; }
        .sidebar a.active { 
             background: #007bff; 
             color: white; 
        }

        .main-content { 
            flex-grow: 1; 
            padding: 30px; 
            overflow-y: auto; }
        h2 { 
            border-bottom: 2px solid #ddd; 
            padding-bottom: 10px; 
            color: #333; }
        
        .alert { padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        
        .report-card { background: white; border-left: 5px solid #007bff; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 20px; border-radius: 4px; padding: 20px; }
        .report-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px; }
        .meta-info { display: block; font-size: 0.85rem; color: #666; margin-top: 5px; }
        .badge { padding: 5px 10px; border-radius: 12px; font-weight: bold; font-size: 0.8rem; }
        .badge-resolved { background: #d4edda; color: #155724; }
        .badge-pending { background: #fff3cd; color: #856404; }
        .report-footer { display: flex; justify-content: space-between; align-items: center; margin-top: 15px; border-top: 1px solid #eee; padding-top: 15px; }
        .btn-approve { background: #28a745; color: white; border: none; padding: 8px 12px; cursor: pointer; border-radius: 4px; }
        .btn-delete { background: #dc3545; color: white; border: none; padding: 8px 12px; cursor: pointer; border-radius: 4px; }
        .btn-disabled { background: #ccc; color: white; border: none; padding: 8px 12px; cursor: not-allowed; border-radius: 4px; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header">Admin Panel</div>
        <a href="dashboard.php">Dashboard</a>
        <a href="reports.php" class="active">All Reports</a>
        <a href="users_details.php">Users Details</a>
        <a href="ban_warning.php">Ban or Warning</a>
        <a href="alert.php">Send Alert</a>
        <a href="admin_logout.php" style="margin-top:auto; background:#d9534f; text-align:center;">Logout</a>
    </div>

    <div class="main-content">
        <h2>All Crime Reports</h2>
        
        <?php echo $msg; ?>

        <?php 
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $isResolved = ($row['status'] == 'Resolved');
                $statusBadge = $isResolved ? '<span class="badge badge-resolved">Resolved</span>' : '<span class="badge badge-pending">Pending</span>';

                $location_display = "Lat: " . ($row['lat'] ?? 'N/A') . ", Lng: " . ($row['lng'] ?? 'N/A');
        ?>
        
       <div class="report-card" style="border-left-color: <?php echo $isResolved ? '#28a745' : '#ffc107'; ?>;">
            
            <div class="report-header">
                <div>
                    <strong style="font-size: 1.1rem;">
                        <?php echo $row['id']; ?>: 
                        <?php echo htmlspecialchars(substr($row['content'], 0, 80)); ?>
                    </strong>
                    <span class="meta-info">
                        Posted by: <strong><?php echo htmlspecialchars($row['reporter_name']); ?></strong> 
                        (<?php echo htmlspecialchars($row['reporter_email']); ?>)
                    </span>
                </div>
                <div><?php echo $statusBadge; ?></div>
            </div>

            <div class="report-body">
                <p><strong>📍 Location:</strong> <?php echo htmlspecialchars($location_display); ?></p>
                <p><strong>📅 Date:</strong> <?php echo date('F j, Y, g:i a', strtotime($row['created_at'])); ?></p>
                
                <div style="background: #f8f9fa; padding: 10px; border-radius: 4px; margin-top: 10px; color: #444; line-height: 1.5;">
                    <?php echo nl2br(htmlspecialchars($row['content'])); ?>
                </div>

                <?php if(!empty($row['image'])): ?>
                    <div style="margin-top:10px;">
                        <a href="uploads/<?php echo $row['image']; ?>" target="_blank" style="color:#007bff; text-decoration:none;">📷 View Attached Image</a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="report-footer">
                <div style="font-size: 0.9rem; color: #555;">
                   Type: <strong><?php echo htmlspecialchars(ucfirst($row['category_name'] ?? 'General')); ?></strong>
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <?php if (!$isResolved): ?>
                    <form action="" method="POST" style="margin:0;">
                        <input type="hidden" name="report_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="approve_report" class="btn-approve">✔ Mark Resolved</button>
                    </form>
                    <?php else: ?>
                        <button class="btn-disabled" disabled>✔ Resolved</button>
                    <?php endif; ?>

                    <form action="" method="POST" style="margin:0;" onsubmit="return confirm('Permanently delete this report?');">
                        <input type="hidden" name="report_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="delete_report" class="btn-delete">🗑️ Delete</button>
                    </form>
                </div>
            </div>

        </div>

        <?php 
            }
        } else {
            echo "<div style='text-align:center; padding:40px; color:#666; font-size:1.1rem;'>No reports found in the database.</div>";
        } 
        ?>

    </div>

</body>
</html>