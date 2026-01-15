<?php
session_start();
include 'db.php';

if (!isset($_SESSION['is_admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

$msg = "";
$prefill_id = "";

if (isset($_GET['user_id'])) {
    $prefill_id = intval($_GET['user_id']);
}

if (isset($_POST['ban_user'])) {
    $user_id = intval($_POST['user_id']);
    $action_type = $_POST['action']; 
    
    // Determine the status string
    if ($action_type == 'ban') {
        $status = 'Banned';
    } elseif ($action_type == 'suspend') {
        $status = 'Suspended';
    } elseif ($action_type == 'unban') {
        $status = 'user'; // Reset to default role
    }
    
    // Update the user's role in the database
    $sql = "UPDATE users SET role='$status' WHERE id=$user_id";
    
    if(mysqli_query($conn, $sql)) {
        if ($status == 'user') {
            $msg = "User #$user_id is now Active (Unbanned).";
        } else {
            $msg = "User #$user_id status updated to: $status";
        }
    } else {
        $msg = "Error updating user: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<head>
    <title>Ban or Suspend</title>
    <style>
        body { 
            margin: 0; font-family: 'Segoe UI', sans-serif; 
            background: #f4f6f9; 
            display: flex; 
            height: 100vh; }
        .sidebar { 
            width: 250px; 
            background: #343a40; 
            color: white; 
            display: flex; 
            flex-direction: column; 
            flex-shrink: 0; }
        .sidebar-header { 
            padding: 20px; 
            font-weight: bold; 
            background: #212529; 
            text-align: center; 
            font-size: 1.2rem; }
        .sidebar a { 
            display: block; 
            padding: 15px 20px; 
            color: #c2c7d0; 
            text-decoration: none; 
            border-bottom: 1px solid #3f474e; 
            transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { 
            background: #007bff; 
            color: white; 
            padding-left: 25px; }
        .main-content { 
            flex-grow: 1; 
            padding: 20px; 
            overflow-y: auto; }
        
        .form-card { 
            background: white; 
            padding: 30px; 
            border-radius: 8px; 
            max-width: 500px; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        input, select { 
            width: 100%; 
            padding: 10px; 
            margin: 10px 0; 
            border: 1px solid #ccc; 
            border-radius: 4px; 
            box-sizing: border-box;}
        .btn-danger { 
            background: #dc3545; 
            color: white; 
            padding: 10px; 
            border: none; 
            width: 100%; 
            cursor: pointer; 
            border-radius: 4px; 
            font-weight: bold;}
        .alert { 
            background: #d4edda; 
            color: #155724; 
            padding: 10px; 
            margin-bottom: 15px; 
            border-radius: 4px; 
            border: 1px solid #c3e6cb; }
    </style>
</head>
<body>
    
    <div class="sidebar">
        <div class="sidebar-header">Admin Panel</div>
        <a href="dashboard.php">Dashboard</a>
        <a href="reports.php">Reports</a>
        <a href="users_details.php">Users Details</a>
        <a href="ban_warning.php" class="active">Ban or Warning</a>
        <a href="alert.php">Send Alert</a>
        <a href="admin_logout.php" style="margin-top:auto; background:#d9534f; text-align:center;">Logout</a>
    </div>

    <div class="main-content">
        <h2>Manage User Status</h2>
        
        <?php if($msg): ?>
            <div class="alert"><?php echo $msg; ?></div>
        <?php endif; ?>

        <div class="form-card">
            <h3>Ban/Suspend User</h3>
            <form action="" method="POST">
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