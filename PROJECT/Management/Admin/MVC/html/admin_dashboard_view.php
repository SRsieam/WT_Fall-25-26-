<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/admin_dashboard.css">
</head>
<body>
 
    <div class="sidebar">
        <div class="sidebar-header"> Admin Panel</div>
        <a href="admin_dashboard_controller.php" class="active"> Dashboard</a>
        <a href="admin_reports_controller.php"> Reports</a>
        <a href="admin_users_details_controller.php"> Users Details</a>
        <a href="admin_ban_warning_controller.php"> Ban or Warning</a>
        <a href="admin_alert_controller.php"> Send Alert</a>
        <a href="admin_map_controller.php"> Map</a>
        <a href="admin_logout.php" style="margin-top:auto; background:#d9534f; text-align:center;">Logout</a>
    </div>
 
    <div class="main-content">
        <h2>Dashboard Overview</h2>
       
        <div class="grid">
            <div class="card"><h3>Total Reports</h3><h1><?php echo $total_reports; ?></h1></div>
            <div class="card" style="border-top-color: #dc3545;"><h3>Pending</h3><h1><?php echo $pending_reports; ?></h1></div>
            <div class="card" style="border-top-color: #28a745;"><h3>Users</h3><h1><?php echo $total_users; ?></h1></div>
        </div>
 
        <h2 id="reports">Recent Reports</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr><th>ID</th><th>Reporter</th><th>Content</th><th>Status</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)) {
                        $statusClass = ($row['status'] == 'Resolved') ? 'status-resolved' : 'status-pending';
                    ?>
                    <tr>
                        <td>#<?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['reporter_name']); ?></td>
                       
                        <td><?php echo htmlspecialchars(substr($row['content'], 0, 50)) . '...'; ?></td>
                       
                        <td><span class="<?php echo $statusClass; ?>"><?php echo $row['status']; ?></span></td>
                        <td>
                            <?php if($row['status'] == 'Pending') { ?>
                                <form action="admin_dashboard_controller.php" method="POST">
                                    <input type="hidden" name="report_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="resolve_report" class="btn-green">Resolve</button>
                                </form>
                            <?php } else { echo "Done"; } ?>
                        </td>
                    </tr>
                    <?php }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
 
</body>
</html>