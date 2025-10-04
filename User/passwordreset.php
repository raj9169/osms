<?php 
define('TITLE', 'Forgot Password');
define('PAGE', '');
include('../dbConnection.php');

// Import PHPMailer classes into the global namespace 
use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\Exception; 

require '../PHPMailer/Exception.php'; 
require '../PHPMailer/PHPMailer.php'; 
require '../PHPMailer/SMTP.php'; 

if(isset($_REQUEST['reset'])) {
    $rEmail = mysqli_real_escape_string($con, trim($_REQUEST['email']));
    $sql = "SELECT * FROM userlogin WHERE r_email='$rEmail'";
    $result = $con->query($sql);

    if($result->num_rows == 1){
        $row = $result->fetch_assoc();
        $rName = $row['r_name'];
        $rPass = $row['r_password'];
    } else {
        echo "<script> alert('No account found with this email.'); </script>";
        echo "<script> location.href='passwordreset.php'; </script>";
        die();
    }

    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host       = 'in-v3.mailjet.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'aa358cc7d693e565cf8ed4b6639f48d3';
    $mail->Password   = 'bad4076378769a3192a02db1209166fb';
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom('eservice.osms@gmail.com', 'OSMS');
    $mail->addAddress($row['r_email']); 

    $mail->isHTML(true); 
    $mail->Subject = 'Your Password Recovery - OSMS'; 
    $mail->Body    = "<h3>Hello $rName,</h3><p>Your password is: <strong>$rPass</strong></p>";

    if(!$mail->send()) { 
        $msg = "<div class='alert alert-danger'>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</div>";
    } else { 
        $msg = "<div class='alert alert-success'>Password has been sent to your email.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo TITLE ?></title>

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Custom CSS -->
<style>
    body {
        background: #f4f7fa;
        font-family: 'Segoe UI', sans-serif;
    }
    .card {
        border-radius: 12px;
        border: none;
    }
    .card-shadow {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
    .form-floating > input {
        border-radius: 8px;
    }
    .btn-primary {
        border-radius: 8px;
    }
</style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center align-items-center vh-100">
        <div class="col-md-5 col-sm-8">
            <div class="text-center mb-4">
                <i class="fas fa-stethoscope fa-3x text-primary"></i>
                <h2 class="mt-2 fw-bold">Online Service Management System</h2>
                <p class="text-muted fs-5"><i class="fas fa-user-secret text-primary"></i> Forgot Password</p>
            </div>

            <div class="card card-shadow p-4">
                <?php if(isset($msg)) echo $msg; ?>
                <form action="" method="POST" novalidate>
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                        <label for="email">Email address</label>
                        <div class="invalid-feedback">
                            Please provide a valid email.
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold" name="reset">
                        <i class="fas fa-envelope"></i> Send Password
                    </button>
                </form>
            </div>

            <div class="text-center mt-3">
                <a href="../index.php" class="btn btn-outline-secondary fw-bold">
                    <i class="fas fa-home"></i> Back to Home
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Bootstrap form validation
(() => {
    'use strict'
    const forms = document.querySelectorAll('form')
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})();
</script>

</body>
</html>
