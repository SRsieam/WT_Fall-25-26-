<?php
session_start();
include 'db.php';

if (!isset($_SESSION['is_admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

$msg = "";
$msg_type = "";

// --- SEND ALERT LOGIC ---
if (isset($_POST['send_alert'])) {
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $type = mysqli_real_escape_string($conn, $_POST['alert_type']); 

    // 1. Deactivate existing alerts in 'alert' table
    mysqli_query($conn, "UPDATE alert SET is_active = 0");

    // 2. Insert new alert into 'alert' table
    $sql = "INSERT INTO alert (message, type, is_active) VALUES ('$message', '$type', 1)";
    
    if (mysqli_query($conn, $sql)) {
        $msg = "Broadcast sent successfully! Users will see it immediately.";
        $msg_type = "success-msg";
    } else {
        $msg = "Error sending alert: " . mysqli_error($conn);
        $msg_type = "error-msg"; 
    }
}

// --- STOP ALERT LOGIC ---
if (isset($_POST['stop_alert'])) {
    // Deactivate all alerts in 'alert' table
    $sql = "UPDATE alert SET is_active = 0";
    if (mysqli_query($conn, $sql)) {
        $msg = "All alerts have been stopped.";
        $msg_type = "success-msg";
    }
}
?>

<!DOCTYPE html>
<head>
    <title>Send Broadcast Alert</title>
    <style>
        body { margin: 0; font-family: 'Segoe UI', sans-serif; background: #f4f6f9; display: flex; height: 100vh; }
        
        /* Sidebar CSS */
        .sidebar { width: 250px; background: #343a40; color: white; display: flex; flex-direction: column; flex-shrink: 0; }
        .sidebar-header { padding: 20px; font-weight: bold; background: #212529; text-align: center; font-size: 1.2rem; }
        .sidebar a { display: block; padding: 15px 20px; color: #c2c7d0; text-decoration: none; border-bottom: 1px solid #3f474e; transition: 0.3s; }
        .sidebar a:hover { background: #007bff; color: white; padding-left: 25px; }
        .sidebar a.active { background: #007bff; color: white; }

        .main-content { flex-grow: 1; padding: 30px; }
        
        .form-card { background: white; padding: 30px; border-radius: 8px; max-width: 600px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        
        label { display: block; margin-top: 15px; font-weight: bold; color: #555; }
        textarea, select { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px; font-family: inherit; }
        
        .btn-send { background: #28a745; color: white; padding: 12px; border: none; width: 100%; cursor: pointer; margin-top: 20px; border-radius: 4px; font-size: 1rem; }
        .btn-stop { background: #dc3545; color: white; padding: 12px; border: none; width: 100%; cursor: pointer; margin-top: 10px; border-radius: 4px; font-size: 1rem; }
        
        .success-msg { background: #d4edda; color: #155724; padding: 15px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #c3e6cb; }
        .error-msg { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    
    <div class="sidebar">
        <div class="sidebar-header">Admin Panel</div>
        <a href="dashboard.php">Dashboard</a>
        <a href="reports.php">Reports</a>
        <a href="users_details.php">Users Details</a>
        <a href="ban_warning.php">Ban or Warning</a>
        <a href="alert.php" class="active">Send Alert</a>
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

            <form method="POST">
                <label>Alert Type:</label>
                <select name="alert_type">
                    <option value="info">Info (Blue)</option>
                    <option value="warning">Warning (Yellow)</option>
                    <option value="danger">Critical (Red)</option>
                    <option value="success">Success (Green)</option>
                </select>

                <label>Message:</label>
                <textarea name="message" rows="5" placeholder="Enter your announcement here..." required></textarea>

                <button type="submit" name="send_alert" class="btn-send">🚀 Send Broadcast</button>
                <button type="submit" name="stop_alert" class="btn-stop">🛑 Stop Active Alerts</button>
            </form>
        </div>
    </div>

</body>
</html>