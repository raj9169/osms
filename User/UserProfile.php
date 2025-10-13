<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Only show errors in development, not production
// ini_set('display_errors', 0); // Use this in production
// error_reporting(E_ALL & ~E_NOTICE); // Use this in production
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

// Get user data using prepared statement
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
        
        // Validate name (only letters, spaces, and basic punctuation)
        if (!preg_match("/^[a-zA-Z\s\.\-']+$/", $newName)) {
            $passmsg = '<div class="alert alert-danger mt-3" role="alert">❌ Invalid name format</div>';
        } else {
            // Update using prepared statement
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
<html>
<head>
  <title>User Dashboard</title>
  <link href="../css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark bg-dark">
  <a class="navbar-brand ms-3" href="#">Dashboard</a>
</nav>
<div class="col-sm-10 mt-5">
  <div class="card shadow-lg border-0 rounded-4">
    <div class="card-header bg-secondary text-white text-center">
      <h4 class="mb-0"><i class="fas fa-user"></i> User Profile</h4>
    </div>
    <div class="card-body p-4">
      <form action="" method="POST">
        <div class="mb-3">
          <label for="rEmail" class="form-label fw-bold">Email</label>
          <input type="email" class="form-control" id="rEmail" value="<?php echo htmlspecialchars($rEmail); ?>" readonly>
        </div>
        <div class="mb-3">
          <label for="rName" class="form-label fw-bold">Name</label>
          <input type="text" class="form-control" id="rName" name="rName" 
                 value="<?php echo htmlspecialchars($rName); ?>" 
                 pattern="[a-zA-Z\s\.\-']+" 
                 title="Only letters, spaces, dots, hyphens, and apostrophes are allowed"
                 required>
        </div>
        <div class="d-flex justify-content-between">
          <button type="submit" class="btn btn-success px-4" name="nameupdate">
            <i class="fas fa-save"></i> Update
          </button>
        </div>
      </form>
      <?php if(isset($passmsg)){ echo $passmsg; } ?>
    </div>
  </div>
</div>

<?php include('includes/footer.php'); ?>
</body>
</html>
