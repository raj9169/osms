<?php 
define('TITLE','Change Password');
define('PAGE','ChangePassword');
include('includes/header.php'); 
include('../dbConnection.php');
//session_start();
if($_SESSION['is_login']){
   $rEmail=$_SESSION['rEmail'];
}else{
   echo "<script>location.href='login.php'</script>";
}

if(isset($_REQUEST['passupdate'])){
    if($_REQUEST['rPassword']== ""){
        $passmsg='<div class="alert alert-warning text-center mt-3" role="alert">⚠️ Please fill all fields.</div>';
    }else{
        $rPass = $_REQUEST['rPassword'];
        $sql= "UPDATE userlogin SET r_password='$rPass' WHERE r_email='$rEmail'";
        if($con->query($sql) == TRUE){
            $passmsg='<div class="alert alert-success text-center mt-3" role="alert">✅ Password updated successfully!</div>';
        }else{
            $passmsg='<div class="alert alert-danger text-center mt-3" role="alert">❌ Unable to update password.</div>';
        }
    }
}
?>

<div class="col-sm-9 col-md-10 mt-5">
  <div class="d-flex justify-content-center">
    <div class="card shadow-lg p-4 w-50">
      <h3 class="text-center text-primary mb-4">🔒 Change Password</h3>
      <form action="" method="POST">
        <div class="form-group">
          <label for="inputEmail">Email</label>
          <input type="email" class="form-control" id="inputEmail" value="<?php echo $rEmail; ?>" readonly>
        </div>

        <div class="form-group mt-3">
          <label for="inputPassword">New Password</label>
          <input type="password" class="form-control" id="inputnewpassword" placeholder="Enter New Password" name="rPassword">
        </div>

        <div class="text-center mt-4">
          <button type="submit" class="btn btn-primary px-4" name="passupdate">Update</button>
          <button type="reset" class="btn btn-secondary px-4 ml-2">Reset</button>
        </div>

        <!-- Alert Messages -->
        <?php if(isset($passmsg)){ echo $passmsg; } ?>
      </form>
    </div>
  </div>
</div>

<?php include('includes/footer.php'); ?>
