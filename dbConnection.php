<?php
// Database configuration
$db_host = "localhost";
$db_user = "osms_user";
$db_password = "secure";  // You need to set a password
$db_name = "osms_db";
$db_port = 3306;  // Try 3306 first (default port)

// Create connection
$con = new mysqli($db_host, $db_user, $db_password, $db_name, $db_port);

// Check connection with detailed error message
if ($con->connect_error) {
    die("Database Connection Failed: " . $con->connect_error . 
        " [Host: $db_host, Port: $db_port, User: $db_user]");
}

// Set charset
$con->set_charset("utf8mb4");

// For debugging (remove in production)
// echo "<!-- Database connected successfully -->";
?>