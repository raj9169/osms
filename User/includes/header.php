<?php
// Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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

if ($result && $result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $rName = $row['r_name'];
} else {
    $message = '<div class="alert alert-danger mt-3" role="alert">❌ User not found in database</div>';
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

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>User Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #6f42c1, #6610f2);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      margin: 0;
    }
    .profile-card {
      max-width: 500px;
      width: 100%;
      background: #fff;
      border: none;
      border-radius: 1rem;
      box-shadow: 0 8px 30px rgba(0,0,0,0.2);
      transition: 0.3s ease;
    }
    .profile-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 10px 35px rgba(0,0,0,0.25);
    }
    .card-header {
      background: linear-gradient(90deg, #6f42c1, #6610f2);
    }
    .card-header h4 {
      font-weight: 600;
    }
    .btn-success {
      background: linear-gradient(90deg, #28a745, #218838);
      border: none;
      transition: 0.3s ease;
    }
    .btn-success:hover {
      background: linear-gradient(90deg, #218838, #1e7e34);
      transform: scale(1.03);
    }
    footer {
      width: 100%;
      background-color: #212529;
      color: #fff;
      text-align: center;
      padding: 0.8rem 0;
      position: fixed;
      bottom: 0;
      left: 0;
    }
  </style>
</head>
<body>

  <div class="container my-5">
    <div class="profile-card mx-auto">
      <div class="card-header text-white text-center py-3">
        <h4 class="mb-0"><i class="fas fa-user-circle me-2"></i> User Profile</h4>
      </div>
      <div class="card-body p-4">
        <form action="" method="POST" novalidate>
          <div class="mb-3">
            <label for="rEmail" class="form-label fw-bold">Email</label>
            <input type="email" class="form-control" id="rEmail" 
                   value="<?php echo htmlspecialchars($rEmail); ?>" readonly>
          </div>
          <div class="mb-3">
            <label for="rName" class="form-label fw-bold">Name</label>
            <input type="text" class="form-control" id="rName" name="rName" 
                   value="<?php echo htmlspecialchars($rName); ?>" 
                   required pattern="[a-zA-Z\s\.\-']+"
                   title="Only letters, spaces, dots, hyphens, and apostrophes allowed">
          </div>
          <div class="text-center">
            <button type="submit" class="btn btn-success px-5 py-2" name="nameupdate">
              <i class="fas fa-save me-2"></i> Update
            </button>
          </div>
        </form>
        <?php if (!empty($message)) echo $message; ?>
      </div>
    </div>
  </div>

  <footer>
    <small>&copy; 2025 YourSite. All rights reserved.</small>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
