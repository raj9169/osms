<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!defined('TITLE')) define('TITLE', 'User Profile');
if (!defined('PAGE')) define('PAGE', 'UserProfile');

include_once('./includes/header.php');
include('../dbConnection.php');

// Session security
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header('Location: login.php');
    exit();
}

$rEmail = $_SESSION['rEmail'];
$rName = '';
$passmsg = '';

// Get user data
$sql = "SELECT r_name FROM userlogin WHERE r_email = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $rEmail);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $rName = $row['r_name'];
}
$stmt->close();

// Handle profile update
if (isset($_REQUEST['nameupdate'])) {
    if (empty(trim($_REQUEST['rName']))) {
        $passmsg = '<div class="alert alert-warning mt-3" role="alert">⚠️ Please enter your name</div>';
    } else {
        $newName = trim($_REQUEST['rName']);
        if (!preg_match("/^[a-zA-Z\s\.\-']+$/", $newName)) {
            $passmsg = '<div class="alert alert-danger mt-3" role="alert">❌ Invalid name format</div>';
        } else {
            $sql = "UPDATE userlogin SET r_name = ? WHERE r_email = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("ss", $newName, $rEmail);
            if ($stmt->execute()) {
                $rName = $newName;
                $passmsg = '<div class="alert alert-success mt-3" role="alert">✅ Profile updated successfully</div>';
            } else {
                $passmsg = '<div class="alert alert-danger mt-3" role="alert">❌ Unable to update profile</div>';
            }
            $stmt->close();
        }
    }
}
?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
