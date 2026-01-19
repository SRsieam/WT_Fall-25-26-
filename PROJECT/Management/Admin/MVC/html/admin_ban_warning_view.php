<!DOCTYPE html>
<html lang="en">
<head>
    <title>Ban or Suspend</title>
    <link rel="stylesheet" href="../css/admin_ban_warning.css">
</head>
<body>
    
    <div class="sidebar">
        <div class="sidebar-header">Admin Panel</div>
        <a href="admin_dashboard_controller.php">Dashboard</a>
        <a href="admin_reports_controller.php">Reports</a>
        <a href="admin_users_details_controller.php">Users Details</a>
        <a href="admin_ban_warning_controller.php" class="active">Ban or Warning</a>
        <a href="admin_alert_controller.php">Send Alert</a>
        <a href="admin_map_controller.php">Live Map</a>
        <a href="admin_logout.php" style="margin-top:auto; background:#d9534f; text-align:center;">Logout</a>
    </div>

    <div class="main-content">
        <h2>Manage User Status</h2>
        
        <?php if($msg): ?>
            <div class="alert"><?php echo $msg; ?></div>
        <?php endif; ?>

        <div class="form-card">
            <h3>Ban/Suspend User</h3>
            <form action="admin_ban_warning_controller.php" method="POST">
                <label>User ID:</label>
                <input type="number" name="user_id" value="<?php echo $prefill_id; ?>" placeholder="Enter User ID manually" required>
                
                <label>Action:</label>
                <select name="action">
                    <option value="ban">Ban User Permanently</option>
                    <option value="suspend">Suspend for 7 Days</option>
                    <option value="unban">Unban / Activate User</option>
                </select>

                <button type="submit" name="ban_user" class="btn-danger">Apply Action</button>
            </form>
        </div>
    </div>

</body>
</html>