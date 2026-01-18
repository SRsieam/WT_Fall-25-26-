<!DOCTYPE html>
<head>
    <title>Send Broadcast Alert</title>
    <link rel="stylesheet" href="../css/admin_alert.css">
</head>
<body>
    
    <div class="sidebar">
        <div class="sidebar-header">Admin Panel</div>
        <a href="admin_dashboard_controller.php">Dashboard</a>
        <a href="admin_reports_controller.php">Reports</a>
        <a href="admin_users_details_controller.php">Users Details</a>
        <a href="admin_ban_warning_controller.php">Ban or Warning</a>
        <a href="admin_alert_controller.php" class="active">Send Alert</a>
        <a href="admin_logout.php" style="margin-top:auto; background:#d9534f; text-align:center;">Logout</a>
    </div>

    <div class="main-content">
        <h2>Broadcast System</h2>
        
        <?php if($msg): ?>
            <div class="<?php echo $msg_type; ?>"><?php echo $msg; ?></div>
        <?php endif; ?>

        <div class="form-card">
            <h3>Send a Message to All Users</h3>
            <p style="color:#666;">This will appear as a popup on their dashboard.</p>

            <form action="admin_alert_controller.php" method="POST">
                <label>Alert Type:</label>
                <select name="alert_type">
                    <option value="info">Info</option>
                    <option value="warning">Warning</option>
                    <option value="danger">Critical</option>
                    <option value="success">Success</option>
                </select>

                <label>Message:</label>
                <textarea name="message" rows="5" placeholder="Enter your announcement here..." required></textarea>

                <button type="submit" name="send_alert" class="btn-send"> Send Broadcast</button>
                <button type="submit" name="stop_alert" class="btn-stop"> Stop Active Alerts</button>
            </form>
        </div>
    </div>

</body>
</html>