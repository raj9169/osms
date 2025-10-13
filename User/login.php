<?php
include('../dbConnection.php');

// Session security
if (session_status() == PHP_SESSION_NONE) {
    session_start();
    session_regenerate_id(true); // Prevent session fixation
}

// Redirect if already logged in
if (isset($_SESSION['is_login']) && $_SESSION['is_login'] === true) {
    header('Location: UserProfile.php');
    exit();
}

$msg = '';

if (isset($_REQUEST['rEmail'])) {
    $rEmail = trim($_REQUEST['rEmail']);
    $rPassword = $_REQUEST['rPassword']; // Don't trim passwords
    
    // Input validation
    if (empty($rEmail)) {
        $msg = '<div class="alert alert-danger mt-2">Please enter your Email</div>';
    } else if (empty($rPassword)) {
        $msg = '<div class="alert alert-danger mt-2">Please enter your Password</div>';
    } else if (!filter_var($rEmail, FILTER_VALIDATE_EMAIL)) {
        $msg = '<div class="alert alert-danger mt-2">Please enter a valid email address</div>';
    } else {
        // Use prepared statement to prevent SQL injection
        $sql = "SELECT r_email, r_password FROM userlogin WHERE r_email = ? LIMIT 1";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $rEmail);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            
            // IMPORTANT: You should use password_verify() for hashed passwords
            // Currently you're storing plain text passwords - this is UNSECURE!
            
            // For now, using plain text comparison (INSECURE - FIX THIS)
            if ($rPassword === $row['r_password']) {
                $_SESSION['is_login'] = true;
                $_SESSION['rEmail'] = $rEmail;
                $_SESSION['login_time'] = time();
                
                header('Location: UserProfile.php');
                exit();
            } else {
                $msg = '<div class="alert alert-warning mt-2">Invalid Email or Password</div>';
            }
        } else {
            $msg = '<div class="alert alert-warning mt-2">Invalid Email or Password</div>';
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/all.min.css">
  <style>
    body {
      background: linear-gradient(135deg, #4facfe, #00f2fe);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .login-card {
      border-radius: 20px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.2);
      background: #fff;
      padding: 2rem;
    }
    .login-title {
      font-size: 1.8rem;
      font-weight: bold;
      color: #007bff;
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
            <i class="fas fa-stethoscope fa-2x text-primary"></i>
            <h2 class="login-title mt-2">Online Service Management</h2>
            <p class="text-muted"><i class="fas fa-user-secret"></i> User Login</p>
          </div>
          <form action="" method="POST">
            <div class="mb-3">
              <label class="form-label"><i class="fas fa-user"></i> Email</label>
              <input type="email" class="form-control" name="rEmail" placeholder="Enter your email" 
                     value="<?php echo isset($_POST['rEmail']) ? htmlspecialchars($_POST['rEmail']) : ''; ?>" 
                     required>
            </div>
            <div class="mb-3">
              <label class="form-label"><i class="fas fa-lock"></i> Password</label>
              <input type="password" class="form-control" name="rPassword" placeholder="Enter your password" required>
            </div>
            <?php if(isset($msg)){echo $msg;} ?>
            <button type="submit" class="btn btn-primary w-100 btn-custom mt-2">Login</button>
          </form>
          <div class="d-flex justify-content-between mt-3">
            <a href="passwordreset.php" class="text-decoration-none">Forgot Password?</a>
            <a href="../index.php" class="text-decoration-none">Back to Home</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="../js/all.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
