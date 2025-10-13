<?php
//include_once('includes/header.php');
include('../dbConnection.php');

// Check if user is logged in
if(!isset($_SESSION['is_login']) || !$_SESSION['is_login']){
   echo "<script>location.href='login.php'</script>";
   exit();
}

$rEmail = $_SESSION['rEmail'];
$rName = "";
$passmsg = "";

// Get user data from database
$sql = "SELECT r_name FROM userlogin WHERE r_email='$rEmail'";
$result = $con->query($sql);

if ($result) {
    if($result->num_rows == 1){
        $row = $result->fetch_assoc();
        $rName = $row['r_name'];
    } else {
        $passmsg = '<div class="alert alert-danger mt-3" role="alert">❌ User not found in database</div>';
    }
} else {
    $passmsg = '<div class="alert alert-danger mt-3" role="alert">❌ Database query failed: ' . $con->error . '</div>';
}

// Handle profile update
if(isset($_REQUEST['nameupdate'])){
   if(empty($_REQUEST['rName'])){
      $passmsg = '<div class="alert alert-warning mt-3" role="alert">⚠️ Please fill all fields</div>';
   } else {
      $rName = trim($_REQUEST['rName']);
      $sql = "UPDATE userlogin SET r_name = '$rName' WHERE r_email = '$rEmail'";
      
      if($con->query($sql) === TRUE){
         $passmsg = '<div class="alert alert-success mt-3" role="alert">✅ Profile updated successfully</div>';
         // Update session if needed
         $_SESSION['rName'] = $rName;
      } else {
         $passmsg = '<div class="alert alert-danger mt-3" role="alert">❌ Unable to update profile: ' . $con->error . '</div>';
      }
   }
}
?>

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
          <input type="text" class="form-control" id="rName" name="rName" value="<?php echo htmlspecialchars($rName); ?>" required>
        </div>
        <div class="d-flex justify-content-between">
          <button type="submit" class="btn btn-success px-4" name="nameupdate">
            <i class="fas fa-save"></i> Update
          </button>
        </div>
      </form>
      <?php 
      if(!empty($passmsg)){ 
          echo $passmsg; 
      } 
      ?>
    </div>
  </div>
</div>

<?php include('includes/footer.php'); ?>
