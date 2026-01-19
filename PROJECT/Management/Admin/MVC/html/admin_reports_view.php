<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage All Reports</title>
    <link rel="stylesheet" href="../css/admin_reports.css">
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header">Admin Panel</div>
        <a href="admin_dashboard_controller.php">Dashboard</a>
        <a href="admin_reports_controller.php" class="active">All Reports</a>
        <a href="admin_users_details_controller.php">Users Details</a>
        <a href="admin_ban_warning_controller.php">Ban or Warning</a>
        <a href="admin_alert_controller.php">Send Alert</a>
        <a href="admin_map_controller.php">Live Map</a>
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
                        #<?php echo $row['id']; ?>: 
                        <?php echo htmlspecialchars(substr($row['content'], 0, 80)); ?>...
                    </strong>
                    <span class="meta-info">
                        Posted by: <strong><?php echo htmlspecialchars($row['reporter_name']); ?></strong> 
                         < <?php echo htmlspecialchars($row['reporter_email']); ?> >
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

                <td>
    <?php if (!empty($row["image"])): ?>
        <a href="../../../User/MVC/uploads/<?php echo $row['image']; ?>" 
           target="_blank" 
           style="color: #007bff; font-weight: bold; text-decoration: none;">
           🖼️ View Uploaded Picture
        </a>
    <?php else: ?>
        <span style="color: #999; font-size: 12px;">No Evidence Provided</span>
    <?php endif; ?>
</td>
            </div>

            <div class="report-footer">
                <div style="font-size: 0.9rem; color: #555;">
                   Type: <strong><?php echo htmlspecialchars(ucfirst($row['category_name'] ?? 'General')); ?></strong>
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <?php if (!$isResolved): ?>
                    <form action="admin_reports_controller.php" method="POST" style="margin:0;">
                        <input type="hidden" name="report_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="approve_report" class="btn-approve">✔ Mark Resolved</button>
                    </form>
                    <?php else: ?>
                        <button class="btn-disabled" disabled>✔ Resolved</button>
                    <?php endif; ?>

                    <form action="admin_reports_controller.php" method="POST" style="margin:0;" onsubmit="return confirm('Permanently delete this report?');">
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