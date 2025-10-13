<?php
// Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//include_once('includes/topbar.php'); // ✅ changed from header.php
include('../dbConnection.php');

// Check if user is logged in
if (!isset($_SESSION['is_login']) || !$_SESSION['is_login']) {
    echo "<script>location.href='login.php'</script>";
    exit();
}

$rEmail = $_SESSION['rEmail'];
$rName = "";
$message = "";

// ✅ Use prepared statement for security
$stmt = $con->prepare("SELECT r_name FROM userlogin WHERE r_email = ?");
$stmt->bind_param("s", $rEmail);
$stmt->execute();
$result = $stmt->get_result();

if ($result) {
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $rName = $row['r_name'];
    } else {
        $message = '<div class="alert alert-danger mt-3" role="alert">❌ User not found in database</div>';
    }
} else {
    $message = '<div class="alert alert-danger mt-3" role="alert">❌ Database query failed</div>';
}

// Handle profile update
if (isset($_POST['nameupdate'])) {
    if (empty(trim($_POST['rName']))) {
        $message = '<div class="alert alert-warning mt-3" role="alert">⚠️ Please fill all fields</div>';
    } else {
        $rName = trim($_POST['rName']);
        $stmt = $con->prepare("UPDATE userlogin SET r_name = ? WHERE r_email = ?");
        $stmt->bind_param("ss", $rName, $rEmail);
        
        if ($stmt->execute()) {
            $message = '<div class="alert alert-success mt-3" role="alert">✅ Profile updated successfully</div>';
            $_SESSION['rName'] = $rName;
        } else {
            $message = '<div class="alert alert-danger mt-3" role="alert">❌ Unable to update profile</div>';
        }
    }
}
?>

<div class="col-sm-10 my-5 px-3">
  <div class="card shadow-lg border-0 rounded-4">
    <div class="card-header bg-secondary text-white text-center">
      <h4 class="mb-0"><i class="fas fa-user"></i> User Profile</h4>
    </div>
    <div class="card-body p-4">
      <form action="" method="POST">
        <div class="mb-3">
          <label for="rEmail" class="form-label fw-bold">Email</label>
          <input type="email" class="form-control" id="rEmail" 
                 value="<?php echo htmlspecialchars($rEmail); ?>" readonly>
        </div>
        <div class="mb-3">
          <label for="rName" class="form-label fw-bold">Name</label>
          <input type="text" class="form-control" id="rName" name="rName" 
                 value="<?php echo htmlspecialchars($rName); ?>" required>
        </div>
        <div class="d-flex justify-content-between">
          <button type="submit" class="btn btn-success px-4" name="nameupdate">
            <i class="fas fa-save"></i> Update
          </button>
        </div>
      </form>
      <?php 
      if (!empty($message)) { 
          echo $message; 
      } 
      ?>
    </div>
  </div>
</div>

<?php include('includes/footer.php'); ?>
<footer class="bg-dark text-white text-center p-2 mt-5">
  <small>&copy; 2025 YourSite</small>
</footer>
</body>
</html>
