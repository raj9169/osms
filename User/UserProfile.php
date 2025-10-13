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

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>User Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #6f42c1, #6610f2);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      color: #212529;
    }
    .profile-card {
      max-width: 500px;
      width: 100%;
      background: #fff;
      border: none;
      border-radius: 1rem;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
      overflow: hidden;
      transition: all 0.3s ease;
    }
    .profile-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 6px 25px rgba(0, 0, 0, 0.25);
    }
    .card-header {
      background: linear-gradient(90deg, #6f42c1, #6610f2);
    }
    .btn-success {
      background: linear-gradient(90deg, #28a745, #218838);
      border: none;
      transition: 0.3s;
    }
    .btn-success:hover {
      background: linear-gradient(90deg, #218838, #1e7e34);
      transform: scale(1.03);
    }
  </style>
</head>
<body>

<div class="container my-5">
  <div class="profile-card mx-auto shadow">
    <div class="card-header text-white text-center py-3">
      <h4 class="mb-0"><i class="fas fa-user-circle"></i> User Profile</h4>
    </div>
    <div class="card-body p-4">
      <form action="" method="POST" novalidate>
        <div class="mb-3">
          <label for="rEmail" class="form-label fw-bold">Email</label>
          <input type="email" class="form-control" id="rEmail" 
                 value="<?php echo htmlspecialchars($rEmail); ?>" readonly>
        </div>
        <div class="mb-3">
          <label for="rName" class="form-label fw-bold">Name</label>
          <input type="text" class="form-control" id="rName" name="rName"
                 value="<?php echo htmlspecialchars($rName); ?>"
                 pattern="[a-zA-Z\s\.\-']+"
                 title="Only letters, spaces, dots, hyphens, and apostrophes are allowed"
                 required>
        </div>
        <div class="text-center">
          <button type="submit" class="btn btn-success px-5 py-2" name="nameupdate">
            <i class="fas fa-save"></i> Update Profile
          </button>
        </div>
      </form>
      <?php if (!empty($passmsg)) echo $passmsg; ?>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
