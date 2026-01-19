<?php
session_start();
include 'db.php';

// Authentication check
if (!isset($_SESSION['is_admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// --- UPDATED SQL QUERY WITH FALLBACK LOGIC ---
// COALESCE ensures that if created_at is NULL, it returns the current timestamp instead of N/A
$sql = "SELECT 
            u.id as user_id, 
            u.name, 
            u.email, 
            u.phone, 
            u.role,
            COALESCE(u.created_at, NOW()) as register_date, 
            COUNT(p.id) as total_posts,
            SUM(CASE WHEN p.status = 'Resolved' THEN 1 ELSE 0 END) as solved_posts,
            MAX(p.created_at) as last_post_date
        FROM users u
        LEFT JOIN posts p ON u.id = p.user_id
        GROUP BY u.id
        ORDER BY u.id DESC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Users Details | Admin Control</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6f9; padding: 25px; margin: 0; }
        h2 { color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        
        table { 
            width: 100%; border-collapse: collapse; background: white; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-radius: 10px; overflow: hidden;
        }
        
        th { 
            padding: 15px; border-bottom: 2px solid #dee2e6; text-align: center;
            background: #343a40; color: white; font-size: 0.85rem; 
            text-transform: uppercase; letter-spacing: 0.5px;
        }
        
        td { padding: 12px 15px; border-bottom: 1px solid #eee; text-align: center; color: #495057; font-size: 0.85rem; }
        tr:hover { background: #f1f3f5; transition: 0.2s; }

        .status-banned { color: #dc3545; font-weight: bold; background: #f8d7da; padding: 4px 8px; border-radius: 4px; }
        .status-suspended { color: #fd7e14; font-weight: bold; background: #fff3cd; padding: 4px 8px; border-radius: 4px; }
        .status-dash { font-weight: bold; color: #6c757d; }

        .badge { padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: bold; display: inline-block; }
        .badge-solved { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .badge-pending { background: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        
        .back-btn {
            display: inline-block; margin-bottom: 20px; text-decoration: none;
            background: #343a40; color: white; padding: 10px 20px;
            border-radius: 5px; font-size: 0.9rem; font-weight: 500; transition: 0.3s;
        }
        .back-btn:hover { background: #007bff; }
        
        .manage-link {
            text-decoration: none; color: #007bff; font-weight: bold;
            border: 1.5px solid #007bff; padding: 5px 10px; border-radius: 5px;
            font-size: 0.8rem; transition: 0.3s;
        }
        .manage-link:hover { background: #007bff; color: white; }
    </style>
</head>
<body>
    
    <h2>👥 All Users Details</h2>
    <a href="dashboard.php" class="back-btn">← Back to Admin Dashboard</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Register Date</th>
                <th>Total Posts</th>
                <th>Solved</th>
                <th>Pending</th>
                <th>Last Activity</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($result)) { 
                $pending = $row['total_posts'] - $row['solved_posts'];
                $role = $row['role'];
                $display_status = ($role == 'Banned') ? 'BANNED' : (($role == 'Suspended') ? 'SUSPENDED' : '-');
                $status_class = ($role == 'Banned') ? 'status-banned' : (($role == 'Suspended') ? 'status-suspended' : 'status-dash');
            ?>
            <tr>
                <td><strong>#<?php echo $row['user_id']; ?></strong></td>
                <td style="text-align:left;"><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                
                <td>
                    <?php 
                        // If the date is valid, format it; otherwise, provide a placeholder
                        $date = $row['register_date'];
                        if ($date && $date !== '0000-00-00 00:00:00') {
                            echo date('M d, Y', strtotime($date)); 
                        } else {
                            echo "<span style='color:#999;'>Recent</span>";
                        }
                    ?>
                </td>

                <td><?php echo $row['total_posts']; ?></td>
                <td><span class="badge badge-solved"><?php echo $row['solved_posts']; ?></span></td>
                <td><span class="badge badge-pending"><?php echo $pending; ?></span></td>

                
                
                <td>
                    <?php 
                        echo $row['last_post_date'] 
                        ? date('Y-m-d H:i', strtotime($row['last_post_date']))
                        : "<small style='color:#999;'>No Posts</small>"; 
                    ?>
                </td>
                
                <td><span class="<?php echo $status_class; ?>"><?php echo $display_status; ?></span></td>

                <td>
                    <a href="ban_warning.php?user_id=<?php echo $row['user_id']; ?>" class="manage-link">Manage Account</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>