<?php
session_start();
// Path adjusted to look into the 'db' folder relative to the 'php' folder
include "../db/db_connection.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    // Using your custom mysqli_real_escape_index function exactly as provided
    $email = mysqli_real_escape_index($conn, trim($_POST["email"]));
    $password = trim($_POST["password"]);

    if (empty($email) || empty($password)) 
    {
        echo "Please enter email and password";
    } else {
 
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);

        if ($row = mysqli_fetch_assoc($result)) {
            // Verify password using the same hash logic
            if (password_verify($password, $row["password"])) 
            {
                $_SESSION["user_id"] = $row["id"];
                $_SESSION["user_name"] = $row["name"];
                echo "success";
            } else 
            {
                echo "Incorrect password";
            }
        } else {
            echo "Email not found";
        }
    }
}

/**
 * Custom function from raw code
 * Keeps logic "same to same" as requested.
 */
function mysqli_real_escape_index($conn, $data) 
{
    return mysqli_real_escape_string($conn, $data);
}
?>