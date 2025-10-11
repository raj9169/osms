<?php
// Database configuration
$db_host = "localhost";
$db_user = "root";
$db_password = "123";
$db_name = "osms_db";
$db_port = 3306;

// Create connection
$con = new mysqli($db_host, $db_user, $db_password, $db_name, $db_port);

// Check connection
if ($con->connect_error) {
    echo "❌ Connection failed: " . $con->connect_error . "<br>";
    echo "Details: [Host: $db_host, Port: $db_port, User: $db_user]<br>";
}
?>
