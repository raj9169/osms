<?php 
define('TITLE', 'Forgot');
define('PAGE', '');
include('../dbConnection.php');
// Import PHPMailer classes into the global namespace 
use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\Exception; 
 
require '../PHPMailer/Exception.php'; 
require '../PHPMailer/PHPMailer.php'; 
require '../PHPMailer/SMTP.php'; 
if(isset($_REQUEST['reset']))
{
$rEmail=mysqli_real_escape_string($con,trim($_REQUEST['email']));
$sql="SELECT * FROM userlogin WHERE r_email='$rEmail'";
$result=$con->query($sql);
if($result->num_rows==1){
   $row=$result->fetch_assoc();
   $rName=$row['r_name'];
   $rPass=$row['r_password'];
}
else{
  echo "<script> alert('You cannot registered with this email.')</script>";
   echo "<script>location.href='passwordreset.php'</script>";
  die();
}
 
$mail = new PHPMailer; 

        $mail->isSMTP();
        $mail->Host       = 'in-v3.mailjet.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'aa358cc7d693e565cf8ed4b6639f48d3'; // Replace
        $mail->Password   = 'bad4076378769a3192a02db1209166fb'; // Replace
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;                 // TCP port to connect to 
 
// Sender info 
        $mail->setFrom('eservice.osms@gmail.com', 'OSMS');
        $mail->addAddress('eservice.osms@gmail.com');
 
// Add a recipient 
$mail->addAddress($row['r_email']); 
 
//$mail->addCC('cc@example.com'); 
//$mail->addBCC('bcc@example.com'); 
 
// Set email format to HTML 
$mail->isHTML(true); 
 
// Mail subject 
$mail->Subject = 'Email from Localhost by OSMS'; 
 
// Mail body content 
$bodyContent = '<h1>Hello,</h1>'.$rName; 
$bodyContent .= '<p>Your Password is</p>'.$rPass; 
$mail->Body    = $bodyContent; 
 
// Send email 
if(!$mail->send()) { 
    echo 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo;
} else { 
    echo "<script> alert('Message has been sent.')</script>";
    echo "<script>location.href='login.php'</script>";
} 
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">


     <!-----font awesome----->
     <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
      

    <!---Custom css---->
    <link rel="stylesheet" href="../css/custom.css">

    <title><?php echo TITLE ?></title>
</head>
<body>
     <div class="text-center mt-5 mb-3" style="font-size:30px;">
  <i class="fas fa-stethoscope"></i>
     <span>Online Service Management System</span>
  </div>
  <p class="text-center" style="font-size:30px"> <i class="fas fa-user-secret text-primary"></i>Forgot Password</p>
    <div class="container-fluid">
      <div class="row justify-content-center mt-5">
         <div class="col-sm-6 col-md-4">
         <form action="" class="shadow-lg p-4">
           <div class="form-group">
             <i class="fas fa-user"></i><label for="email" class="font-weight-bold pl-2">Email</label>
             <input type="email" class="form-control" placeholder="Email" name="email">
             <small>We'll never share your Email</small>
           </div>
           <div>
           <button class="btn btn-outline-primary mt-3 font-weight-bold btn-block shadow-sm" name="reset">Send Password</button>
           <?php if(isset($msg)){echo $msg;} ?>
           </div>
         </form>
               <div class=" text-center mt-3 ">
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