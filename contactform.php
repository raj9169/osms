<!-- <?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '/var/www/html/OSMS_PHP/PHPMailer/Exception.php';
require '/var/www/html/OSMS_PHP/PHPMailer/PHPMailer.php';
require '/var/www/html/OSMS_PHP/PHPMailer/SMTP.php';

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
        $mail->Username   = 'aa358cc7d693e565cf8ed4b6639f48d3';
        $mail->Password   = 'bad4076378769a3192a02db1209166fb';
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
</script> -->


<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer from vendor directory
require 'vendor/autoload.php';

$success_msg = '';
$error_msg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Basic validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error_msg = '<div class="alert alert-danger mt-3">Please fill all fields.</div>';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = '<div class="alert alert-danger mt-3">Please enter a valid email address.</div>';
    } else {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'in-v3.mailjet.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'aa358cc7d693e565cf8ed4b6639f48d3';
            $mail->Password   = 'bad4076378769a3192a02db1209166fb';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Recipients
            $mail->setFrom('eservice.osms@gmail.com', 'OSMS System');
            $mail->addAddress('eservice.osms@gmail.com', 'OSMS Admin');
            $mail->addReplyTo($email, $name);

            // Content
            $mail->isHTML(true);
            $mail->Subject = "Contact Form: " . $subject;
            $mail->Body    = "
                <h2>New Contact Message</h2>
                <p><strong>Name:</strong> $name</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Subject:</strong> $subject</p>
                <p><strong>Message:</strong></p>
                <p>" . nl2br(htmlspecialchars($message)) . "</p>
                <hr>
                <p><small>Sent from OSMS Contact Form</small></p>
            ";
            
            $mail->AltBody = "
                New Contact Message\n
                Name: $name\n
                Email: $email\n
                Subject: $subject\n
                Message: $message\n
            ";

            if ($mail->send()) {
                $success_msg = '<div class="alert alert-success mt-3">✅ Message has been sent successfully! We will get back to you soon.</div>';
            }

        } catch (Exception $e) {
            $error_msg = '<div class="alert alert-danger mt-3">❌ Message could not be sent. Please try again later.</div>';
            error_log("PHPMailer Error: " . $mail->ErrorInfo);
        }
    }
}
?>

<!-- Contact Form Section -->
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-envelope me-2"></i>Contact Us</h4>
                </div>
                <div class="card-body p-4">
                    <?php 
                    // Display messages
                    if (!empty($success_msg)) echo $success_msg;
                    if (!empty($error_msg)) echo $error_msg;
                    ?>
                    
                    <form method="POST" id="contactForm" onsubmit="return validateContactForm();">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="name" id="cName" placeholder="Your Name" 
                                           value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required>
                                    <label for="cName"><i class="fas fa-user me-2"></i>Your Name</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" name="email" id="cEmail" placeholder="Your Email" 
                                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                                    <label for="cEmail"><i class="fas fa-envelope me-2"></i>Email Address</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="subject" id="cSubject" placeholder="Subject" 
                                   value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>" required>
                            <label for="cSubject"><i class="fas fa-tag me-2"></i>Subject</label>
                        </div>

                        <div class="form-floating mb-3">
                            <textarea class="form-control" name="message" id="cMessage" style="height: 150px;" 
                                      placeholder="Your Message" required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                            <label for="cMessage"><i class="fas fa-comment me-2"></i>Your Message</label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg" name="submit">
                                <i class="fas fa-paper-plane me-2"></i> Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function validateContactForm() {
    const name = document.getElementById("cName").value.trim();
    const email = document.getElementById("cEmail").value.trim();
    const subject = document.getElementById("cSubject").value.trim();
    const message = document.getElementById("cMessage").value.trim();

    // Name validation (letters and spaces only)
    if (!/^[A-Za-z\s]{2,}$/.test(name)) {
        alert("Please enter a valid name (letters and spaces only, minimum 2 characters).");
        document.getElementById("cName").focus();
        return false;
    }

    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert("Please enter a valid email address.");
        document.getElementById("cEmail").focus();
        return false;
    }

    // Subject validation
    if (subject.length < 5) {
        alert("Please enter a subject with at least 5 characters.");
        document.getElementById("cSubject").focus();
        return false;
    }

    // Message validation
    if (message.length < 10) {
        alert("Please enter a message with at least 10 characters.");
        document.getElementById("cMessage").focus();
        return false;
    }

    return true;
}

// Real-time validation
document.addEventListener('DOMContentLoaded', function() {
    const nameField = document.getElementById('cName');
    const subjectField = document.getElementById('cSubject');
    
    if (nameField) {
        nameField.addEventListener('input', function() {
            this.value = this.value.replace(/[^A-Za-z\s]/g, '');
        });
    }
    
    if (subjectField) {
        subjectField.addEventListener('input', function() {
            this.value = this.value.replace(/[^A-Za-z0-9\s\-_.,!?]/g, '');
        });
    }
});
</script>