<?php
session_start();
include "../db/db.php"; // Include DB connection to fetch posts

// Fetch all crime reports (Joined with Users to show who posted)
$sql = "SELECT r.*, u.name as reporter_name 
        FROM crime_reports r 
        JOIN users u ON r.user_id = u.user_id 
        ORDER BY r.created_at DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>

    <div class="admin-nav">
        <span>Admin Panel</span>
        <a href="login.php">Logout</a>
    </div>

    <div class="container">
        <h2>System Management</h2>

        <div class="grid">
            
            <div class="card">
                <h3>User Management</h3>
                <p>Ban users who violate rules.</p>
                <form action="../php/admin_controller.php" method="POST">
                    <input type="text" name="user_id" placeholder="Enter User ID/Name to Ban" required>
                    <button type="submit" name="ban_user" class="btn-red">Ban User</button>
                </form>
            </div>

            <div class="card">
                <h3>Crime Reports</h3>
                <p>Resolve pending incidents.</p>
                <form action="../php/admin_controller.php" method="POST">
                    <input type="text" name="report_id" placeholder="Enter Report ID" required>
                    <button type="submit" name="resolve_report" class="btn-green">Mark Resolved</button>
                </form>
            </div>

            <div class="card">
                <h3>System Alert</h3>
                <p>Send emergency notification to all.</p>
                <form action="../php/admin_controller.php" method="POST">
                    <textarea name="message" placeholder="Emergency Message..." required></textarea>
                    <button type="submit" name="broadcast_alert" class="btn-orange">Broadcast</button>
                </form>
            </div>
        </div>

        <div class="table-container">
            <h3>All Crime Reports</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Reporter</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_assoc($result)) { 
                            $statusClass = ($row['status'] == 'Resolved') ? 'status-resolved' : 'status-pending';
                    ?>
                    <tr>
                        <td>#<?php echo $row['report_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['reporter_name']); ?></td>
                        <td><strong><?php echo htmlspecialchars($row['title']); ?></strong></td>
                        <td><?php echo htmlspecialchars(substr($row['description'], 0, 50)) . '...'; ?></td>
                        <td><?php echo htmlspecialchars($row['location_address']); ?></td>
                        <td><span class="<?php echo $statusClass; ?>"><?php echo $row['status']; ?></span></td>
                        <td>
                            <?php if($row['status'] == 'Pending') { ?>
                                <form action="../php/admin_controller.php" method="POST" style="margin:0;">
                                    <input type="hidden" name="report_id" value="<?php echo $row['report_id']; ?>">
                                    <button type="submit" name="resolve_report" class="btn-small-green">Resolve</button>
                                </form>
                            <?php } else { echo "Done"; } ?>
                        </td>
                    </tr>
                    <?php 
                        } 
                    } else {
                        echo "<tr><td colspan='7'>No reports found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>

</body>
</html>