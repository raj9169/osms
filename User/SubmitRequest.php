<?php 
define('TITLE','Requester Submit');
define('PAGE','SubmitRequest');
include('includes/header.php');
include('../dbConnection.php');
//session_start();

if($_SESSION['is_login']){
   $rEmail=$_SESSION['rEmail'];
}else{
   echo "<script>location.href='login.php'</script>";
}

if(isset($_REQUEST['submitrequest'])){
  if(($_REQUEST['nameinfo']=="") || ($_REQUEST['requestdesc']=="") || ($_REQUEST['requestername']=="") ||
   ($_REQUEST['requesteradd1']=="") || ($_REQUEST['requesteradd2']=="") || ($_REQUEST['requestercity']=="") || 
   ($_REQUEST['requesterstate']=="") || ($_REQUEST['requesterzip']=="") || ($_REQUEST['requesteremail']=="") ||
    ($_REQUEST['requestermobile']=="") || ($_REQUEST['requesterdate']=="") ){
       $regmsg= '<div class="alert alert-warning mt-3" role="alert">⚠️ All Fields Are Required</div>';
    }else{
      $rinfo=$_REQUEST["nameinfo"];
      $rdesc=$_REQUEST["requestdesc"];
      $rname=$_REQUEST["requestername"];
      $radd1=$_REQUEST["requesteradd1"];
      $radd2=$_REQUEST["requesteradd2"];
      $rcity=$_REQUEST["requestercity"];
      $rstate=$_REQUEST["requesterstate"];
      $rzip=$_REQUEST["requesterzip"];
      $remail=$_REQUEST["requesteremail"];
      $rmobile=$_REQUEST["requestermobile"];
      $rdate=$_REQUEST["requesterdate"];
      
      $sql="INSERT INTO submitrequest(request_info,request_desc,request_name,request_add1,request_add2,request_city,request_state,request_zip,request_email,request_mobile,request_date) 
            VALUES('$rinfo','$rdesc','$rname','$radd1','$radd2','$rcity','$rstate','$rzip','$remail','$rmobile','$rdate')";
      
      if($con->query($sql)== TRUE){
          $genid=mysqli_insert_id($con);
          $_SESSION['myid']=$genid;
          echo "<script>location.href='SubmitRequestSuccess.php'</script>";
      }else{
          $regmsg= '<div class="alert alert-danger mt-3" role="alert">❌ Unable to submit request</div>';
      }
    }
}
?>

<!-- Modern Form Layout -->
<div class="container-fluid">
  <div class="row justify-content-center mt-5">
    <div class="col-lg-8 col-md-10">
      <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-secondary text-white text-center rounded-top-4">
          <h4 class="mb-0">Submit a Service Request</h4>
        </div>
        <div class="card-body p-4">
          <form id="requestForm" action="" method="POST" onsubmit="return validateForm()">

            <div class="mb-3">
              <label for="inputRequestInfo" class="form-label">Request Info</label>
              <input type="text" class="form-control" id="inputRequestInfo" name="nameinfo" required>
            </div>

            <div class="mb-3">
              <label for="inputRequestDescription" class="form-label">Description</label>
              <textarea class="form-control" id="inputRequestDescription" rows="3" name="requestdesc" required></textarea>
            </div>

            <div class="mb-3">
              <label for="inputName" class="form-label">Full Name</label>
              <input type="text" class="form-control" id="inputName" name="requestername" required>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="inputAddress" class="form-label">Address Line 1</label>
                <input type="text" class="form-control" id="inputAddress" name="requesteradd1" required>
              </div>
              <div class="col-md-6 mb-3">
                <label for="inputAddress2" class="form-label">Address Line 2</label>
                <input type="text" class="form-control" id="inputAddress2" name="requesteradd2" required>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="inputCity" class="form-label">City</label>
                <input type="text" class="form-control" id="inputCity" name="requestercity" required>
              </div>
              <div class="col-md-4 mb-3">
                <label for="inputState" class="form-label">State</label>
                <input type="text" class="form-control" id="inputState" name="requesterstate" required>
              </div>
              <div class="col-md-2 mb-3">
                <label for="inputZip" class="form-label">Zip</label>
                <input type="text" class="form-control" id="inputZip" name="requesterzip" required>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="inputEmail" class="form-label">Email</label>
                <input type="email" class="form-control" id="inputEmail" name="requesteremail" required>
              </div>
              <div class="col-md-3 mb-3">
                <label for="inputMobile" class="form-label">Mobile</label>
                <input type="text" class="form-control" id="inputMobile" name="requestermobile" required>
              </div>
              <div class="col-md-3 mb-3">
                <label for="inputDate" class="form-label">Date</label>
                <input type="date" class="form-control" id="inputDate" name="requesterdate" required>
              </div>
            </div>

            <div class="d-flex justify-content-between">
              <button type="submit" class="btn btn-success px-4" name="submitrequest">🚀 Submit</button>
              <button type="reset" class="btn btn-outline-secondary px-4">🔄 Reset</button>
            </div>
          </form>
          <?php if(isset($regmsg)){echo $regmsg;} ?>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
// Client-side validation
function validateForm() {
  let name = document.getElementById("inputName");
  let mobile = document.getElementById("inputMobile");
  let zip = document.getElementById("inputZip");
  let email = document.getElementById("inputEmail");

  // Reset styles
  [name, mobile, zip, email].forEach(el => el.classList.remove("is-invalid"));

  // Name - alphabets only
  let nameRegex = /^[A-Za-z ]+$/;
  if (!nameRegex.test(name.value.trim())) {
    alert("❌ Name should contain alphabets only.");
    name.classList.add("is-invalid");
    name.focus();
    return false;
  }

  // Mobile - 10 digits
  let mobileRegex = /^[0-9]{10}$/;
  if (!mobileRegex.test(mobile.value.trim())) {
    alert("❌ Mobile number must be exactly 10 digits.");
    mobile.classList.add("is-invalid");
    mobile.focus();
    return false;
  }

  // Zip - 6 digits
  let zipRegex = /^[0-9]{6}$/;
  if (!zipRegex.test(zip.value.trim())) {
    alert("❌ Pincode must be exactly 6 digits.");
    zip.classList.add("is-invalid");
    zip.focus();
    return false;
  }

  // Email - must be Gmail
  let emailRegex = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;
  if (!emailRegex.test(email.value.trim())) {
    alert("❌ Please enter a valid Gmail address (must end with @gmail.com).");
    email.classList.add("is-invalid");
    email.focus();
    return false;
  }

  return true;
}
</script>

<?php include('includes/footer.php'); ?>
