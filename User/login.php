<?php
include('../dbConnection.php');
session_start();
if(!isset($_SESSION['is_login'])){
if(isset($_REQUEST['rEmail'])){
$rEmail=mysqli_real_escape_string($con,trim($_REQUEST['rEmail']));
$rPassword=mysqli_real_escape_string($con,trim($_REQUEST['rPassword']));
if($rEmail==""){
  echo "<script>alert('Input Your Email Id')</script>";
}
else if($rPassword==""){
    echo "<script>alert('Input Your Password')</script>";
  }else{
$sql="SELECT r_email,r_password FROM userlogin  WHERE r_email= '".$rEmail."' AND r_password = '".$rPassword."' limit 1";
$result=$con->query($sql);
if($result->num_rows == 1){
  $_SESSION['is_login']=TRUE;
  $_SESSION['rEmail']=$rEmail;
   echo "<script>location.href='UserProfile.php';</script>";
}else{
  $msg='<div class="alert alert-warning mt-2">Enter Valid Email Id and Password</div>';
}
}
}
}else{
  echo "<script>location.href='UserProfile.php';</script>";
}
?>








<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!---Bootstrap css--->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    
     <!-----font awesome----->
    <link rel="stylesheet" href="../css/all.min.css">

        <!---Custom css---->
    <link rel="stylesheet" href="css/custom.css">

    <title>Login</title>
</head>
<body>
  <div class="text-center mt-5 mb-3" style="font-size:30px;">
  <i class="fas fa-stethoscope"></i>
     <span>Online Service Management System</span>
  </div>
  <p class="text-center" style="font-size:30px"> <i class="fas fa-user-secret text-primary"></i> User Login</p>
    <div class="container-fluid">
      <div class="row justify-content-center mt-5">
         <div class="col-sm-6 col-md-4">
         <form action="" class="shadow-lg p-4">
           <div class="form-group">
             <i class="fas fa-user"></i><label for="email" class="font-weight-bold pl-2">Email</label>
             <input type="email" class="form-control" placeholder="Email" name="rEmail">
             <small>We'll never share your Email</small>
           </div>
           <div class="form-group">
             <i class="fas fa-key"></i><label for="password" class="font-weight-bold pl-2">Password</label>
             <input type="password" class="form-control" placeholder="Password" name="rPassword">
             <small>We'll never share your Password</small>
           </div>
           <div>
           <button class="btn btn-outline-primary mt-3 font-weight-bold btn-block shadow-sm">Login</button>
           <?php if(isset($msg)){echo $msg;} ?>
           </div>
         </form>
               <div class=" text-center mt-3 ">
                <a href="passwordreset.php" class="btn btn-info font-weight-bold shadow-sm">Forgot</a>
                 <a href="../index.php" class="btn btn-info font-weight-bold shadow-sm">Back to Home</a>
              </div>
         </div>
      </div>
    </div>





     <!----Javascript--->
   <script src="../js/jquery.min.js"></script> 
   <script src="../js/popper.min.js"></script> 
   <script src="../js/all.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>
</body>
</html>