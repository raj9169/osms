<?php
include('../dbConnection.php');
session_start();
if(!isset($_SESSION['is_adminlogin'])){
  if(isset($_REQUEST['aEmail'])){
    $aEmail = mysqli_real_escape_string($con, trim($_REQUEST['aEmail']));
    $aPassword = mysqli_real_escape_string($con, trim($_REQUEST['aPassword']));

    if($aEmail == ""){
      $msg = '<div class="alert alert-danger mt-2">Please enter your Email</div>';
    } else if($aPassword == ""){
      $msg = '<div class="alert alert-danger mt-2">Please enter your Password</div>';
    } else {
      $sql = "SELECT a_email, a_password FROM adminlogin  
              WHERE a_email='".$aEmail."' AND a_password='".$aPassword."' LIMIT 1";
      $result = $con->query($sql);

      if($result->num_rows == 1){
        $_SESSION['is_adminlogin'] = TRUE;
        $_SESSION['aEmail'] = $aEmail;
        echo "<script>location.href='dashboard.php';</script>";
        exit;
      } else {
        $msg = '<div class="alert alert-warning mt-2">Invalid Email or Password</div>';
      }
    }
  }
} else {
  echo "<script>location.href='dashboard.php';</script>";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/all.min.css">
  <style>
    body {
      background: linear-gradient(135deg, #ff512f, #dd2476);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .login-card {
      background: #fff;
      border-radius: 18px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.25);
      padding: 2rem;
    }
    .login-title {
      font-size: 1.8rem;
      font-weight: bold;
      color: #dc3545;
    }
    .form-control {
      border-radius: 12px;
      padding: 12px;
    }
    .btn-custom {
      border-radius: 12px;
      padding: 12px;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-5 col-md-7 col-sm-10">
        <div class="login-card">
          <div class="text-center mb-4">
            <i class="fas fa-user-shield fa-2x text-danger"></i>
            <h2 class="login-title mt-2">Admin Login</h2>
            <p class="text-muted">Online Service Management System</p>
          </div>
          <form action="" method="POST">
            <div class="mb-3">
              <label class="form-label"><i class="fas fa-user"></i> Email</label>
              <input type="email" class="form-control" name="aEmail" placeholder="Enter admin email" required>
            </div>
            <div class="mb-3">
              <label class="form-label"><i class="fas fa-lock"></i> Password</label>
              <input type="password" class="form-control" name="aPassword" placeholder="Enter password" required>
            </div>
            <?php if(isset($msg)){echo $msg;} ?>
            <button type="submit" class="btn btn-danger w-100 btn-custom mt-2">Login</button>
          </form>
          <div class="text-center mt-3">
            <a href="../index.php" class="btn btn-outline-secondary w-100 btn-custom">Back to Home</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="../js/all.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
