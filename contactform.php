<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

// Display errors for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $name    = $_POST['name'] ?? '';
    $email   = $_POST['email'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'in-v3.mailjet.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'aa358cc7d693e565cf8ed4b6639f48d3'; // Replace
        $mail->Password   = 'bad4076378769a3192a02db1209166fb'; // Replace
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('eservice.osms@gmail.com', 'OSMS');
        $mail->addAddress('eservice.osms@gmail.com');

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = "
            <h2>Contact Message</h2>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Message:</strong><br>$message</p>
        ";

        $mail->send();
        echo "<div class='alert alert-success'>Message has been sent successfully!</div>";
    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>Mailer Error: {$mail->ErrorInfo}</div>";
    }
}
?>

<form method="POST" onsubmit="return validateContactForm();">
  <div class="form-floating mb-3">
    <input type="text" class="form-control" name="name" id="cName" placeholder="Name" required>
    <label for="cName">Name</label>
  </div>

  <div class="form-floating mb-3">
    <input type="email" class="form-control" name="email" id="cEmail" placeholder="Email" required>
    <label for="cEmail">Email</label>
  </div>

  <div class="form-floating mb-3">
    <input type="text" class="form-control" name="subject" id="cSubject" placeholder="Subject" required>
    <label for="cSubject">Subject</label>
  </div>

  <div class="form-floating mb-3">
    <textarea class="form-control" name="message" id="cMessage" style="height: 120px;" placeholder="Message" required></textarea>
    <label for="cMessage">Message</label>
  </div>

  <div class="d-grid mb-3">
    <button type="submit" class="btn btn-primary btn-lg" name="submit">Send Message</button>
  </div>
</form>

<script>
function validateContactForm(){
    const name = document.getElementById("cName").value.trim();
    const subject = document.getElementById("cSubject").value.trim();
    if (!/^[A-Za-z\s]+$/.test(name)) {
        alert("Name must be alphabetic only.");
        return false;
    }
    if (!/^[A-Za-z\s]+$/.test(subject)) {
        alert("Subject must be alphabetic only.");
        return false;
    }
    return true;
}
</script>
