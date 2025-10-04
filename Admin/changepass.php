<?php
define('TITLE', 'Change Password');
define('PAGE', 'changepass');
include('includes/header.php'); 
include('../dbConnection.php');

if(!isset($_SESSION['is_adminlogin'])){
    echo "<script> location.href='login.php'; </script>";
}
$aEmail = $_SESSION['aEmail'];

// Handle password update
$passmsg = "";
$passmsg_type = ""; // success, danger, warning
if(isset($_POST['passupdate'])){
    if(empty($_POST['aPassword'])){
        $passmsg = "Please fill all fields.";
        $passmsg_type = "warning";
    } else {
        $aPass = $_POST['aPassword'];
        $sql = "UPDATE adminlogin SET a_password = '$aPass' WHERE a_email = '$aEmail'";
        if($con->query($sql) === TRUE){
            $passmsg = "Password updated successfully!";
            $passmsg_type = "success";
        } else {
            $passmsg = "Unable to update password.";
            $passmsg_type = "danger";
        }
    }
}
?>

<div class="col-sm-9 col-md-10 mt-5">
    <div class="d-flex justify-content-center">
        <div class="card shadow-sm w-50">
            <div class="card-body">
                <h4 class="card-title mb-4 text-center">Change Password</h4>
                <form method="POST">
                    <div class="mb-3">
                        <label for="inputEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="inputEmail" value="<?php echo $aEmail; ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="inputnewpassword" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="inputnewpassword" placeholder="Enter new password" name="aPassword" required>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary" name="passupdate">Update</button>
                        <button type="reset" class="btn btn-secondary">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<?php if(!empty($passmsg)) { ?>
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
  <div id="liveToast" class="toast align-items-center text-bg-<?php echo $passmsg_type; ?> border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">
        <?php echo $passmsg; ?>
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function(){
    var toastEl = document.getElementById('liveToast');
    var toast = new bootstrap.Toast(toastEl);
    toast.show();
});
</script>
<?php } ?>

<?php
include('includes/footer.php'); 
?>
