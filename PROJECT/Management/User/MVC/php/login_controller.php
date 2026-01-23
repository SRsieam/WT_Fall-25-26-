<?php
/**
 * User Login Controller
 * Handles authentication, status checks, and session management.
 */

session_start();
include "../db/db_connection.php"; 

// Initialize variables to prevent undefined errors in the view
$msg = "";
$email = "";

/**
 * Sanitizes input data by removing unnecessary whitespace.
 */
function text_input($data) 
{
    return trim($data);
}

// Process data only when the form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    // Sanitize user inputs to prevent basic SQL injection and formatting issues
    $email = mysqli_real_escape_string($conn, text_input($_POST["email"]));
    $password = text_input($_POST["password"]);

    // Validation: Check if fields are empty
    if (empty($email) || empty($password)) {
        $msg = "Please enter email and password";
    } else {
        // Query to check if the user exists based on email
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);

        // If a record is found, proceed to password verification
        if ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $row["password"])) 
            {
                // check account status (Role-based access control)
                if ($row['role'] == 'Banned') 
                {
                    $msg = "🚫 Access Denied: Your account is permanently banned.";
                } elseif ($row['role'] == 'Suspended') 
                {
                    $msg = "⚠️ Account Suspended: Please contact support.";
                } else {
                    // Successful login: Set session variables
                    $_SESSION["user_id"] = $row["id"];
                    $_SESSION["user_name"] = $row["name"];
                    $_SESSION["is_user_logged_in"] = true;

                    // Update the last login timestamp for the user
                    $user_id = $row["id"];
                    mysqli_query($conn, "UPDATE users SET last_login = NOW() WHERE id = $user_id");

                    // Redirect to the dashboard/homepage
                    header("Location: user_homepage_controller.php"); 
                    exit();
                }
            } else {
                // Password does not match the hashed version in DB
                $msg = "Incorrect password";
            }
        } else {
            // No user found with that specific email
            $msg = "Email not found";
        }
    }
}

// Load the HTML view for the login page
include "../html/login_view.php";
?>