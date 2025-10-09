<?php
echo "PHP is working!<br>";
echo "Current time: " . date('Y-m-d H:i:s') . "<br>";

// Test database connection
$con = new mysqli("localhost", "osms_user", "secure", "osms_db", 3306);

if ($con->connect_error) {
    echo "❌ Database Error: " . $con->connect_error . "<br>";
} else {
    echo "✅ Database Connected!<br>";
    $con->close();
}
?>