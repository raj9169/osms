<?php
echo "PHP is working!<br>";
echo "Current time: " . date('Y-m-d H:i:s') . "<br>";

// Test database connection
$con = new mysqli("tryit-thisdb.c6bc06igsqpj.us-east-1.rds.amazonaws.com", "admin", "Raj9918264088", "osms_db", 3306);

if ($con->connect_error) {
    echo "❌ Database Error: " . $con->connect_error . "<br>";
} else {
    echo "✅ Database Connected!<br>";
    $con->close();
}
?>
