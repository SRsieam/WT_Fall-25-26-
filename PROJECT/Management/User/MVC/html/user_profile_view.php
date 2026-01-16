<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile | Crime Detection</title>
    <link rel="stylesheet" type="text/css" href="../css/user_profile_style.css">
</head>
<body>

<div class="sidebar">
    <h2>CRIME REPORT</h2>
    <a href="user_homepage_controller.php" class="nav-btn">Dashboard</a>
    <a href="user_profile_controller.php" class="nav-btn <?php echo ($mode == 'view' && !$editPost) ? 'active-btn' : ''; ?>">My Profile</a>
    <a href="update_profile_controller.php" class="nav-btn">⚙️ Edit Profile</a>
    <a href="user_profile_controller.php?mode=delete" class="nav-btn <?php echo ($mode == 'delete') ? 'active-btn' : ''; ?>" style="background:#c0392b;">Delete Reports</a>
    <a href="user_logout.php" class="nav-btn" style="margin-top:40px; background:#c0392b;">Logout</a>
</div>

<div class="main">
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h3>Account Information</h3>
            <a href="update_profile_controller.php" style="color:#3498db; text-decoration:none; font-size:13px; font-weight:bold;">Edit Info ⚙️</a>
        </div>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($user_name); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user_data['email'] ?? 'N/A'); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($user_data['phone'] ?? 'N/A'); ?></p>
    </div>

    <?php if ($editPost): ?>
    <div class="card edit-section">
        <h3 style="margin-top:0;">Update Report #<?php echo $editPost['id']; ?></h3>
        <form method="post" action="user_profile_controller.php">
            <input type="hidden" name="id" value="<?php echo $editPost['id']; ?>">
            <label style="font-size:13px; font-weight:bold; color:#2c3e50;">Current Message Content:</label>
            <textarea name="content" rows="4"><?php echo htmlspecialchars($editPost['content']); ?></textarea>
            <div style="margin-top:15px;">
                <button type="submit" name="update" class="btn-save">Save Changes</button>
                <a href="user_profile_controller.php" style="margin-left: 15px; color:#e74c3c; text-decoration:none; font-weight:bold;">Cancel</a>
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
                                <a href="user_profile_controller.php?delete=<?php echo $row['id']; ?>&mode=delete" style="color:#e74c3c; font-weight:bold; text-decoration:none;" onclick="return confirm('Permanently delete this report?')">Confirm Delete</a>
                            <?php else: ?>
                                <a href="user_profile_controller.php?edit=<?php echo $row['id']; ?>" style="color:#3498db; font-weight:bold; text-decoration:none;">Edit</a>
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