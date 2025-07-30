<?php
include('../dbConnection.php'); // Ensure this is correct
$regmsg = '';

if (isset($_REQUEST['rSignup'])) {
    if (empty($_REQUEST['rName']) || empty($_REQUEST['rEmail']) || empty($_REQUEST['rPassword']) || empty($_REQUEST['rMobile'])) {
        $regmsg = '<div class="alert alert-warning">All Fields are Required</div>';
    } else {
        $rName = $_REQUEST["rName"];
        $rEmail = $_REQUEST["rEmail"];
        $rPassword = $_REQUEST["rPassword"];
        $rMobile = $_REQUEST["rMobile"];

        $sql = "SELECT r_email FROM userlogin WHERE r_email='$rEmail'";
        $result = $con->query($sql);

        if ($result->num_rows == 1) {
            $regmsg = '<div class="alert alert-warning">Email Id Already Registered</div>';
        } else {
            $sql = "INSERT INTO userlogin(r_name, r_email, r_password, r_mobile) 
                    VALUES('$rName', '$rEmail', '$rPassword', '$rMobile')";
            $regmsg = ($con->query($sql) === TRUE)
                ? '<div class="alert alert-success">Account successfully created</div>'
                : '<div class="alert alert-danger">Unable to create Account</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Registration</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow">
                <div class="card-body">
                    <h3 class="text-center mb-4">Create an Account</h3>

                    <form method="POST" onsubmit="return validateRegistration();">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="rName" id="rName" placeholder="Full Name">
                            <label for="rName">Full Name</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" name="rEmail" id="rEmail" placeholder="Email">
                            <label for="rEmail">Email Address</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="rMobile" id="rMobile" placeholder="Mobile Number">
                            <label for="rMobile">Mobile Number</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" name="rPassword" id="rPass" placeholder="Password">
                            <label for="rPass">Password</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="rCpass" placeholder="Confirm Password" onkeyup="checkPasswordMatch();">
                            <label for="rCpass">Confirm Password</label>
                        </div>

                        <div id="rMessage" class="mb-3"></div>

                        <div class="d-grid mb-2">
                            <button type="submit" class="btn btn-primary btn-lg" name="rSignup">Sign Up</button>
                        </div>

                        <?php if (!empty($regmsg)) echo "<div class='mt-2'>$regmsg</div>"; ?>
                    </form>

                    <!--Back to Home Button -->
                    <div class="text-center mt-3">
                        <a href="../index.php" class="btn btn-outline-secondary">Back to Home</a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<!-- ✅ Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- ✅ Custom JS Validation -->
<script>
function validateRegistration(){
    var name = document.getElementById("rName").value.trim();
    var email = document.getElementById("rEmail").value.trim();
    var mob = document.getElementById("rMobile").value.trim();
    var pass = document.getElementById("rPass").value;
    var cpass = document.getElementById("rCpass").value;

    if(!name.match(/^[A-Za-z\s]+$/)){
        alert("Name must contain only alphabets.");
        return false;
    }
    if(!email.match(/^[\\w.-]+@[a-zA-Z\\d.-]+\\.[a-zA-Z]{2,6}$/)){
        alert("Invalid Email Format");
        return false;
    }
    if(!mob.match(/^[0-9]{10}$/)){
        alert("Invalid Mobile Number");
        return false;
    }
    if(pass !== cpass){
        alert("Passwords do not match.");
        return false;
    }
    return true;
}

function checkPasswordMatch(){
    var matchDiv = document.getElementById('rMessage');
    if (document.getElementById('rPass').value === document.getElementById('rCpass').value) {
        matchDiv.innerHTML = '<div class="alert alert-success">Passwords match</div>';
    } else {
        matchDiv.innerHTML = '<div class="alert alert-warning">Passwords do not match</div>';
    }
}
</script>

</body>
</html>
