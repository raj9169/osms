<?php 
define('TITLE','Change Password');
define('PAGE','ChangePassword');
include('includes/header.php'); 
include('../dbConnection.php');
session_start();
if($_SESSION['is_login']){
   $rEmail=$_SESSION['rEmail'];
}else{
   echo "<script>location.href='login.php'</script>";
}
if(isset($_REQUEST['passupdate'])){
    if($_REQUEST['rPassword']== ""){
        $passmsg='<div class="alert alert-warning col-sm-6 ml-5 mt-2">Fill All Fields </div>';
    }else{
    $rPass = $_REQUEST['rPassword'];
    $sql= "UPDATE userlogin SET r_password='$rPass' WHERE r_email='$rEmail'";
    if($con->query($sql) ==TRUE){
        $passmsg='<div class="alert alert-success col-sm-6 ml-5 mt-2">Update Successfully </div>';
      }else{
        $passmsg='<div class="alert alert-warning col-sm-6 ml-5 mt-2">Unable to Update</div>';
      }
}
}

?>
<div class="col-sm-9 col-md-10 mt-5">   <!----start user change password from 2nd column--->
<form class="mt-5 mx-5"  action="" method="POST">
   <div class="form-group">
     <label for="inputEmail">Email</label>
     <input type="email" class="form-control" id="inputEmail" value="<?php echo $rEmail; ?>" readonly>
   </div>
    <div class="form-group">
     <label for="inputPassword">New Password</label>
     <input type="password" class="form-control" id="inputnewpassword" placeholder="New Password" name="rPassword">
   </div>
   <button type="submit" class="btn btn-danger mt-4" name="passupdate">Update</button>
   <button type="reset" class="btn btn-secondary mt-4">Reset</button>
   <?php if(isset($passmsg)){echo $passmsg;}  ?>
</form>
</div>    <!----End user change password form 2nd column-->

<?php
include('includes/footer.php');
?>