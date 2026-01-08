<?php
session_start();
include 'db.php'; 

$sql = "SELECT r.*, u.name as reporter_name, u.email as reporter_email 
        FROM crime_reports r 
        LEFT JOIN users u ON r.user_id = u.user_id 
        ORDER BY r.created_at DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<head>
    <title>Manage All Reports</title>
    <style>
        body { margin: 0; font-family: 'Segoe UI', sans-serif; background: #f4f6f9; display: flex; height: 100vh; }
        
        .sidebar { width: 250px; background: #343a40; color: white; display: flex; flex-direction: column; flex-shrink: 0; }
        .sidebar-header { padding: 20px; font-weight: bold; background: #212529; text-align: center; font-size: 1.2rem; }
        .sidebar a { display: block; padding: 15px 20px; color: #c2c7d0; text-decoration: none; border-bottom: 1px solid #3f474e; transition: 0.3s; }
        .sidebar a:hover { background: #007bff; color: white; padding-left: 25px; }
        .sidebar a.active { background: #007bff; color: white; }

        .main-content { flex-grow: 1; padding: 30px; overflow-y: auto; }
        h2 { border-bottom: 2px solid #ddd; padding-bottom: 10px; color: #333; }

        .alert { padding: 10px 15px; border-radius: 4px; margin-bottom: 20px; }
        .alert.success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert.error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        .report-card { background: white; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 20px; overflow: hidden; }
        .report-header { background: #f8f9fa; padding: 15px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
        .report-body { padding: 20px; }
        .report-footer { background: #f1f1f1; padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; }

        .meta-info { font-size: 0.9rem; color: #666; margin-bottom: 10px; }
        .badge { padding: 5px 10px; border-radius: 12px; font-size: 0.8rem; font-weight: bold; }
        .badge-pending { background: #ffeeba; color: #856404; }
        .badge-resolved { background: #d4edda; color: #155724; }
        
        button { cursor: pointer; padding: 8px 12px; border: none; border-radius: 4px; font-weight: bold; transition: 0.2s; }
        .btn-approve { background: #28a745; color: white; }
        .btn-approve:hover { background: #218838; }
        .btn-delete { background: #dc3545; color: white; }
        .btn-delete:hover { background: #c82333; }
        .btn-disabled { background: #ccc; cursor: not-allowed; color: #666; }

    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header">Admin Panel</div>
        <a href="dashboard.php"> Dashboard</a>
        <a href="reports.php" class="active"> All Reports</a>
        <a href="login.php" style="margin-top:auto; background:#d9534f; text-align:center;">Logout</a>
    </div>

    <div class="main-content">
        <h2>All Crime Reports</h2>
        
        <?php if(isset($msg)) echo $msg; ?>

        <?php 
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $isResolved = ($row['status'] == 'Resolved');
                $statusBadge = $isResolved ? '<span class="badge badge-resolved">Resolved</span>' : '<span class="badge badge-pending">Pending</span>';
        ?>
        
        <div class="report-card">
            
            <div class="report-header">
                <div>
                    <strong>Report #<?php echo $row['report_id']; ?>: <?php echo htmlspecialchars($row['title']); ?></strong>
                    <br>
                    <span class="meta-info">Posted by: <strong><?php echo htmlspecialchars($row['reporter_name']); ?></strong> (<?php echo htmlspecialchars($row['reporter_email']); ?>)</span>
                </div>
                <div><?php echo $statusBadge; ?></div>
            </div>

            <div class="report-body">
                <p><strong>📍 Location:</strong> <?php echo htmlspecialchars($row['location_address']); ?></p>
                <p><strong>📅 Date:</strong> <?php echo date('F j, Y, g:i a', strtotime($row['created_at'])); ?></p>
                <hr style="border: 0; border-top: 1px solid #eee; margin: 15px 0;">
                <p><strong>📝 Description:</strong></p>
                <p style="color: #444; line-height: 1.6;"><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
            </div>

            <div class="report-footer">
                <div style="font-size: 0.85rem; color: #777;">
                   Type: <?php echo htmlspecialchars($row['crime_type'] ?? 'General'); ?>
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <?php if (!$isResolved): ?>
                    <form action="reports.php" method="POST" style="margin:0;">
                        <input type="hidden" name="report_id" value="<?php echo $row['report_id']; ?>">
                        <button type="submit" name="approve_report" class="btn-approve">✔ Approve & Resolve</button>
                    </form>
                    <?php else: ?>
                        <button class="btn-disabled" disabled>✔ Resolved</button>
                    <?php endif; ?>

                    <form action="reports.php" method="POST" style="margin:0;" onsubmit="return confirm('Are you sure you want to permanently delete this report?');">
                        <input type="hidden" name="report_id" value="<?php echo $row['report_id']; ?>">
                        <button type="submit" name="delete_report" class="btn-delete">🗑️ Delete</button>
                    </form>
                </div>
            </div>

        </div>

        <?php 
            }
        } else {
            echo "<p style='text-align:center; padding:20px; color:#666;'> No reports found in the database right now</p>";
        } 
        ?>

    </div>

</body>
</html>