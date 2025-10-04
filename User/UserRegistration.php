<?php
include('../dbConnection.php'); 
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #e0f7fa, #fff);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            border-radius: 1rem;
        }
        .form-floating>.form-control:focus~label {
            color: #0d6efd;
        }
        @media (max-width: 576px) {
            .card {
                margin: 1rem;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">

            <div class="card shadow-sm p-4">
                <h3 class="text-center mb-4">Create an Account</h3>

                <?php if (!empty($regmsg)) echo $regmsg; ?>

                <form method="POST" onsubmit="return validateRegistration();">

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="rName" id="rName" placeholder="Full Name">
                        <label for="rName">Full Name</label>
                        <div class="invalid-feedback">Name must contain only alphabets.</div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" name="rEmail" id="rEmail" placeholder="Email">
                        <label for="rEmail">Email Address</label>
                        <div class="invalid-feedback">Enter a valid email.</div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="rMobile" id="rMobile" placeholder="Mobile Number">
                        <label for="rMobile">Mobile Number</label>
                        <div class="invalid-feedback">Enter a 10-digit mobile number.</div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" name="rPassword" id="rPass" placeholder="Password">
                        <label for="rPass">Password</label>
                        <div class="invalid-feedback">Password is required.</div>
                    </div>

                    <div class="form-floating mb-2">
                        <input type="password" class="form-control" id="rCpass" placeholder="Confirm Password" onkeyup="checkPasswordMatch();">
                        <label for="rCpass">Confirm Password</label>
                        <div id="rMessage" class="mt-1"></div>
                    </div>

                    <div class="d-grid mt-3 mb-2">
                        <button type="submit" class="btn btn-primary btn-lg" name="rSignup">Sign Up</button>
                    </div>

                    <div class="text-center">
                        <a href="../index.php" class="btn btn-outline-secondary btn-sm">Back to Home</a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function validateRegistration() {
    let valid = true;

    const name = document.getElementById("rName");
    const email = document.getElementById("rEmail");
    const mob = document.getElementById("rMobile");
    const pass = document.getElementById("rPass");
    const cpass = document.getElementById("rCpass");

    name.classList.remove("is-invalid");
    email.classList.remove("is-invalid");
    mob.classList.remove("is-invalid");
    pass.classList.remove("is-invalid");
    cpass.classList.remove("is-invalid");

    if(!name.value.trim().match(/^[A-Za-z\s]+$/)) { name.classList.add("is-invalid"); valid = false; }
    if(!email.value.trim().match(/^[\w.-]+@[a-zA-Z\d.-]+\.[a-zA-Z]{2,6}$/)) { email.classList.add("is-invalid"); valid = false; }
    if(!mob.value.trim().match(/^[0-9]{10}$/)) { mob.classList.add("is-invalid"); valid = false; }
    if(pass.value === "") { pass.classList.add("is-invalid"); valid = false; }
    if(pass.value !== cpass.value) { cpass.classList.add("is-invalid"); valid = false; }

    return valid;
}

function checkPasswordMatch() {
    const matchDiv = document.getElementById('rMessage');
    const pass = document.getElementById('rPass').value;
    const cpass = document.getElementById('rCpass').value;

    if (cpass === "") {
        matchDiv.innerHTML = '';
    } else if (pass === cpass) {
        matchDiv.innerHTML = '<div class="text-success">Passwords match</div>';
    } else {
        matchDiv.innerHTML = '<div class="text-danger">Passwords do not match</div>';
    }
}
</script>

</body>
</html>
