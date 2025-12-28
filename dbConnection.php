<?php

$host = getenv('DB_HOST');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');
$db   = getenv('DB_NAME');

if (!$host || !$user || !$pass || !$db) {
    die("Environment variables not loaded. Check Secrets Manager or UserData.");
}

$con = new mysqli($host, $user, $pass, $db);

if ($con->connect_error) {
    die("Database Connection Failed");
}

$con->set_charset("utf8mb4");

?>
