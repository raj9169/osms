<?php
define('TITLE','User Profile');
define('PAGE','UserProfile');
include('includes/header.php');
include('../dbConnection.php');
session_start();
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
      $passmsg='<div class="alert alert-warning">Fill All Field</div>';
   }else{
      $rName=$_REQUEST['rName'];
      $sql="UPDATE userlogin SET r_name= '$rName'  WHERE r_email='$rEmail'";
      if($con->query($sql)==TRUE){
         $passmsg='<div class="alert alert-success col-sm-6 ml-5 mt-5" role="alert">Update Successfully</div>';
      }else{
         $passmsg='<div class="alert alert-danger col-sm-6 ml-5 mt-5" role="alert">Unable to Update Successfully</div>';
      }
   }
}
?>

          <div class="col-sm-6 mt-5">       <!----start profile area 2nd col--->
             <form action="" method="POST" class="mx-5">
               <div class="form-group mt-5">
               <label for="inputEmail">Email</label><input type="email" name="rEmail" id="rEmail" class="form-control" value="<?php echo $rEmail; ?>" readonly>
               </div>
               <div class="form-group">
               <label for="rName">Name</label><input type="text" name="rName" id="rName" class="form-control" value="<?php echo $rName; ?>">
               </div>
               <button type="submit" class="btn btn-primary" name="nameupdate">Update</button>
               <?php if(isset($passmsg)){echo $passmsg;} ?>
             </form>

<?php include('includes/footer.php'); ?>
   
</body>
</html>