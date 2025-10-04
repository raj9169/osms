<?php
define('TITLE','User Profile');
define('PAGE','UserProfile');
include('includes/header.php');
include('../dbConnection.php');
//session_start();

if($_SESSION['is_login']){
   $rEmail=$_SESSION['rEmail'];
}else{
   echo "<script>location.href='login.php'</script>";
}

$sql="SELECT r_name FROM userlogin WHERE r_email='$rEmail'";
$result=$con->query($sql);
if($result->num_rows==1){
   $row=$result->fetch_assoc();
   $rName=$row['r_name'];
}

if(isset($_REQUEST['nameupdate'])){
   if($_REQUEST['rName']==""){
      $passmsg='<div class="alert alert-warning mt-3" role="alert">⚠️ Please fill all fields</div>';
   }else{
      $rName=$_REQUEST['rName'];
      $sql="UPDATE userlogin SET r_name= '$rName'  WHERE r_email='$rEmail'";
      if($con->query($sql)==TRUE){
         $passmsg='<div class="alert alert-success mt-3" role="alert">✅ Profile updated successfully</div>';
      }else{
         $passmsg='<div class="alert alert-danger mt-3" role="alert">❌ Unable to update profile</div>';
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
          <input type="email" class="form-control" id="rEmail" value="<?php echo $rEmail; ?>" readonly>
        </div>
        <div class="mb-3">
          <label for="rName" class="form-label fw-bold">Name</label>
          <input type="text" class="form-control" id="rName" name="rName" value="<?php echo $rName; ?>">
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
