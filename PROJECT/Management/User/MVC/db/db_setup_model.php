<?php

include "db_connection.php"; 


$sql1 = "CREATE TABLE IF NOT EXISTS categories (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(100) NOT NULL UNIQUE
)";


$sql_chat = "CREATE TABLE IF NOT EXISTS messages (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    user_id INT(11) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";


$sql_notif = "CREATE TABLE IF NOT EXISTS notifications (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    user_id INT(11) NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";


if (mysqli_query($conn, $sql1)) echo "Table 'categories' checked/created.<br>";
if (mysqli_query($conn, $sql_chat)) echo "Table 'messages' checked/created.<br>";
if (mysqli_query($conn, $sql_notif)) echo "Table 'notifications' checked/created.<br>";


function addColumnIfNeeded($conn, $table, $column, $definition) {
    $check = mysqli_query($conn, "SHOW COLUMNS FROM `$table` LIKE '$column'");
    if (mysqli_num_rows($check) == 0) {
        mysqli_query($conn, "ALTER TABLE `$table` ADD COLUMN $column $definition");
        echo "Column '$column' added to '$table'.<br>";
    } else {
        echo "Column '$column' already exists in '$table'.<br>";
    }
}


addColumnIfNeeded($conn, 'posts', 'category_id', "INT(11) AFTER content");
addColumnIfNeeded($conn, 'posts', 'status', "VARCHAR(20) DEFAULT 'Pending' AFTER image");
addColumnIfNeeded($conn, 'users', 'role', "VARCHAR(10) DEFAULT 'user' AFTER password");

$categories = [
    'Theft', 'Robbery', 'Assault', 'Vandalism', 
    'Fraud', 'Drug Activity', 'Traffic Violation', 
    'Harassment', 'Burglary'
];

foreach ($categories as $cat) {
    $cat_esc = mysqli_real_escape_string($conn, $cat);

    mysqli_query($conn, "INSERT IGNORE INTO categories (category_name) VALUES ('$cat_esc')");
}

echo "<br><b>Database Update Complete!</b> Chat system, notifications, and categories are ready.";
?>