<?php    
define('TITLE', 'Update Technician');
define('PAGE', 'technician');
include('includes/header.php'); 
include('../dbConnection.php');

if (isset($_SESSION['is_adminlogin'])) {
  $aEmail = $_SESSION['aEmail'];
} else {
  echo "<script> location.href='login.php'; </script>";
}

// update
if (isset($_REQUEST['empupdate'])) {
  if (($_REQUEST['empName'] == "") || ($_REQUEST['empCity'] == "") || ($_REQUEST['empMobile'] == "") || ($_REQUEST['empEmail'] == "")) {
    $msg = '<div class="alert alert-warning mt-3" role="alert">⚠️ Please fill all fields</div>';
  } else {
    $eId     = $_REQUEST['empId'];
    $eName   = $_REQUEST['empName'];
    $eCity   = $_REQUEST['empCity'];
    $eMobile = $_REQUEST['empMobile'];
    $eEmail  = $_REQUEST['empEmail'];

    $sql = "UPDATE technician 
            SET empName = '$eName', empCity = '$eCity', empMobile = '$eMobile', empEmail = '$eEmail' 
            WHERE empid = '$eId'";

    if ($con->query($sql) === TRUE) {
      echo "<script>
              alert('✅ Technician details updated successfully!');
              window.location.href = 'technician.php';
            </script>";
      exit;
    } else {
      $msg = '<div class="alert alert-danger mt-3" role="alert">❌ Unable to Update</div>';
    }
  }
}

?>

<div class="col-sm-9 col-md-10 mt-5">
  <div class="card shadow-lg">
    <div class="card-header bg-primary text-white">
      <h4 class="mb-0"><i class="fas fa-user-edit"></i> Update Technician Details</h4>
    </div>
    <div class="card-body">
      <?php
        if (isset($_REQUEST['view'])) {
          $sql = "SELECT * FROM technician WHERE empid = {$_REQUEST['id']}";
          $result = $con->query($sql);
          $row = $result->fetch_assoc();
        }
      ?>
      <form action="" method="POST">
        <div class="form-group mb-3">
          <label for="empId"><strong>Emp ID</strong></label>
          <input type="text" class="form-control" id="empId" name="empId" 
                 value="<?php if(isset($row['empid'])) {echo $row['empid']; }?>" readonly>
        </div>
        <div class="form-group mb-3">
          <label for="empName"><strong>Name</strong></label>
          <input type="text" class="form-control" id="empName" name="empName" 
                 value="<?php if(isset($row['empName'])) {echo $row['empName']; }?>">
        </div>
        <div class="form-group mb-3">
          <label for="empCity"><strong>City</strong></label>
          <input type="text" class="form-control" id="empCity" name="empCity" 
                 value="<?php if(isset($row['empCity'])) {echo $row['empCity']; }?>">
        </div>
        <div class="form-group mb-3">
          <label for="empMobile"><strong>Mobile</strong></label>
          <input type="text" class="form-control" id="empMobile" name="empMobile" 
                 value="<?php if(isset($row['empMobile'])) {echo $row['empMobile']; }?>" 
                 onkeypress="isInputNumber(event)">
        </div>
        <div class="form-group mb-3">
          <label for="empEmail"><strong>Email</strong></label>
          <input type="email" class="form-control" id="empEmail" name="empEmail" 
                 value="<?php if(isset($row['empEmail'])) {echo $row['empEmail']; }?>">
        </div>
        
        <div class="text-center">
          <button type="submit" class="btn btn-success px-4" id="empupdate" name="empupdate">
            <i class="fas fa-save"></i> Update
          </button>
          <a href="technician.php" class="btn btn-secondary px-4">
            <i class="fas fa-times"></i> Cancel
          </a>
        </div>

        <?php if(isset($msg)) { echo $msg; } ?>
      </form>
    </div>
  </div>
</div>

<!-- Only Number for Mobile -->
<script>
  function isInputNumber(evt) {
    var ch = String.fromCharCode(evt.which);
    if (!(/[0-9]/.test(ch))) {
      evt.preventDefault();
    }
  }
</script>

<?php include('includes/footer.php'); ?>
