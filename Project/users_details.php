<?php
session_start();
include 'db.php';

if (!isset($_SESSION['is_admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

$sql = "SELECT 
            u.id as user_id, 
            u.name, 
            u.email, 
            u.phone, 
            u.role,
            'N/A' as register_date, 
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
<html>
<head>
    <title>All Users Details</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #f4f6f9; padding: 20px; }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            background: white; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        th { 
            padding: 12px; 
            border-bottom: 2px solid #ddd; 
            text-align: center;
            background: #343a40; 
            color: white; 
            font-size: 0.95rem; }
        td {
            padding: 10px; 
            border-bottom: 1px solid #ddd; 
            text-align: center; 
            color: #444; }
        tr:hover { 
            background: #f8f9fa; }

        .sidebar { 
            width: 250px; 
            background: #343a40; 
            color: white; 
            display: flex; 
            flex-direction: column; 
            flex-shrink: 0; }
        
        .status-banned { 
            color: #dc3545;
            font-weight: bold; 
        }
        .status-suspended { 
            color: #fd7e14;
            font-weight: bold; 
        }
        .status-dash {
            font-weight: bold;
            color: #6c757d; 
        }

        .badge { 
            padding: 4px 10px; 
            border-radius: 12px; 
            font-size: 0.8rem; 
            font-weight: bold;}
        .badge-solved { 
            background: #d4edda; 
            color: #155724; }
        .badge-pending { 
            background: #fff3cd; 
            color: #856404; }
        
        .back-btn {
            display: inline-block;
            margin-bottom: 15px;
            text-decoration: none;
            background: #343a40;
            color: white;
            padding: 8px 15px;
            border-radius: 4px;
            font-size: 0.9rem;
            transition: 0.3s;
        }
        .back-btn:hover { background: #007bff; }
    </style>
</head>
<body>
    
    <h2>👥 All Users Details</h2>
    <a href="dashboard.php" class="back-btn"> Back</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Register Date</th>
                <th>Total Posts</th>
                <th>Solved Posts</th>
                <th>Pending Posts</th>
                <th>Last Post Date</th>
                <th>Ban/Warning</th> 
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($result)) { 
                $pending = $row['total_posts'] - $row['solved_posts'];
                
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
                <td><?php echo $row['register_date']; ?></td>
                <td><?php echo $row['total_posts']; ?></td>
                <td><span class="badge badge-solved"><?php echo $row['solved_posts']; ?></span></td>
                <td><span class="badge badge-pending"><?php echo $pending; ?></span></td>
                <td>
                    <?php 
                        echo $row['last_post_date'] 
                        ? date('Y-m-d H:i', strtotime($row['last_post_date']))
                        : "No posts"; 
                    ?>
                </td>
                <td>
                    <span class="<?php echo $status_class; ?>"><?php echo $display_status; ?></span>
                </td>
            </tr>
            <?php 
            } ?>
        </tbody>
    </table>

</body>
</html>