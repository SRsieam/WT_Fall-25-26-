<?php
session_start();
include 'db.php'; 

if (isset($_POST['resolve_report'])) {
    $id = intval($_POST['report_id']);
    $sql = "UPDATE crime_reports SET status='Resolved' WHERE report_id=$id";
    mysqli_query($conn, $sql);
    header("Location: dashboard.php#reports"); // Reload page at Reports section
    exit();
}

$total_reports = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM crime_reports"))['c'];
$pending_reports = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM crime_reports WHERE status='Pending'"))['c'];
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM users"))['c'];

$sql = "SELECT r.*, u.name as reporter_name 
        FROM crime_reports r 
        JOIN users u ON r.user_id = u.user_id 
        ORDER BY r.created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        body { margin: 0; font-family: 'Segoe UI', sans-serif; background: #f4f6f9; display: flex; height: 100vh; }
        
        .sidebar { width: 250px; background: #343a40; color: white; display: flex; flex-direction: column; flex-shrink: 0; }
        .sidebar-header { padding: 20px; font-weight: bold; background: #212529; text-align: center; font-size: 1.2rem; }
        .sidebar a { display: block; padding: 15px 20px; color: #c2c7d0; text-decoration: none; border-bottom: 1px solid #3f474e; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { background: #007bff; color: white; padding-left: 25px; }
        
        .main-content { flex-grow: 1; padding: 20px; overflow-y: auto; }
        
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); border-top: 4px solid #007bff; }
        
        .table-container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; }
        input { width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        
        .btn-red { background: #dc3545; color: white; border: none; padding: 10px; width: 100%; cursor: pointer; border-radius: 4px; }
        .btn-green { background: #28a745; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 4px; }
        
        .status-resolved { background: #d4edda; color: #155724; padding: 4px 8px; border-radius: 12px; font-weight: bold; font-size: 0.85rem; }
        .status-pending { background: #fff3cd; color: #856404; padding: 4px 8px; border-radius: 12px; font-weight: bold; font-size: 0.85rem; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header"> Admin Panel</div>
        <a href="dashboard.php" class="active"> Dashboard</a>
        <a href="#reports"> Reports</a>
        <a href="#users"> Users Details</a>
        <a href="#users"> Ban or Warning</a>
        <a href="alert.php"> Send Alert</a>
        <a href="login.php" style="margin-top:auto; background:#d9534f; text-align:center;"> Logout</a>
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
                    <tr><th>ID</th><th>Reporter</th><th>Title</th><th>Status</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)) { 
                        $statusClass = ($row['status'] == 'Resolved') ? 'status-resolved' : 'status-pending';
                    ?>
                    <tr>
                        <td>#<?php echo $row['report_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['reporter_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><span class="<?php echo $statusClass; ?>"><?php echo $row['status']; ?></span></td>
                        <td>
                            <?php if($row['status'] == 'Pending') { ?>
                                <form action="dashboard.php" method="POST">
                                    <input type="hidden" name="report_id" value="<?php echo $row['report_id']; ?>">
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