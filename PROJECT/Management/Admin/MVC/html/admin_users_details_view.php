<!DOCTYPE html>
<html>
<head>
    <title>All Users Details</title>
    <link rel="stylesheet" href="../css/admin_users_details.css">
</head>
<body>
    
    <div class="sidebar">
        <div class="sidebar-header">Admin Panel</div>
        <a href="admin_dashboard_controller.php">Dashboard</a>
        <a href="admin_reports_controller.php">Reports</a>
        <a href="admin_users_details_controller.php" class="active">Users Details</a>
        <a href="admin_ban_warning_controller.php">Ban or Warning</a>
        <a href="admin_alert_controller.php">Send Alert</a>
        <a href="admin_map_controller.php">Live Map</a>
        <a href="admin_logout.php" style="margin-top:auto; background:#d9534f; text-align:center;">Logout</a>
    </div>

    <div class="main-content">
        <h2>👥 All Users Details</h2>
        <a href="admin_dashboard_controller.php" class="back-btn">← Back</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Total Posts</th>
                    <th>Solved</th>
                    <th>Pending</th>
                    <th>Last Post Date</th>
                    <th>Last Login</th>
                    <th>Last Logout</th>
                    <th>Ban/Warning</th> 
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)) { 
                    $pending = $row['total_posts'] - $row['solved_posts'];
                    
                    // Display Logic for Role Status
                    $role = $row['role'];
                    $display_status = '-';
                    $status_class = 'status-dash';

                    if ($role == 'Banned') {
                        $display_status = 'Banned';
                        $status_class = 'status-banned';
                    } elseif ($role == 'Suspended') {
                        $display_status = 'Suspended';
                        $status_class = 'status-suspended';
                    }
                ?>
                <tr>
                    <td><?php echo $row['user_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                    <td><?php echo $row['total_posts']; ?></td>
                    <td><span class="badge badge-solved"><?php echo $row['solved_posts']; ?></span></td>
                    <td><span class="badge badge-pending"><?php echo $pending; ?></span></td>
                    <td>
                        <?php 
                            echo $row['last_post_date'] 
                            ? date('M d, Y H:i', strtotime($row['last_post_date']))
                            : "-"; 
                        ?>
                    </td>
                    
                    <td style="font-size:0.9rem; color:#007bff;">
                        <?php 
                            echo $row['last_login'] 
                            ? date('M d, H:i', strtotime($row['last_login'])) 
                            : '<span style="color:#ccc;">Never</span>'; 
                        ?>
                    </td>

                    <td style="font-size:0.9rem; color:#666;">
                        <?php 
                            echo $row['last_logout'] 
                            ? date('M d, H:i', strtotime($row['last_logout'])) 
                            : '<span style="color:#ccc;">-</span>'; 
                        ?>
                    </td>

                    <td>
                        <span class="<?php echo $status_class; ?>"><?php echo $display_status; ?></span>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</body>
</html>