<?php
define('TITLE', 'Add New Product');
define('PAGE', 'assets');
include('includes/header.php'); 
include('../dbConnection.php');
//session_start();
 if(isset($_SESSION['is_adminlogin'])){
  $aEmail = $_SESSION['aEmail'];
 } else {
  echo "<script> location.href='login.php'; </script>";
 }
if(isset($_REQUEST['psubmit'])){
 // Checking for Empty Fields
 if(($_REQUEST['pname'] == "") || ($_REQUEST['pdop'] == "") || ($_REQUEST['pava'] == "") || ($_REQUEST['ptotal'] == "") || ($_REQUEST['poriginalcost'] == "") || ($_REQUEST['psellingcost'] == "")){
  // msg displayed if required field missing
  $msg = '<div class="alert alert-warning col-sm-6 ml-5 mt-2" role="alert"> Fill All Fileds </div>';
 } else {
  // Assigning User Values to Variable
  $pname = $_REQUEST['pname'];
  $pdop = $_REQUEST['pdop'];
  $pava = $_REQUEST['pava'];
  $ptotal = $_REQUEST['ptotal'];
  $poriginalcost = $_REQUEST['poriginalcost'];
  $psellingcost = $_REQUEST['psellingcost'];
   $sql = "INSERT INTO assets (pname, pdop, pava, ptotal, poriginalcost, psellingcost) VALUES ('$pname', '$pdop','$pava', '$ptotal', '$poriginalcost', '$psellingcost')";
   if($con->query($sql) == TRUE){
    // below msg display on form submit success
    $msg = '<div class="alert alert-success col-sm-6 ml-5 mt-2" role="alert"> Added Successfully </div>';
   } else {
    // below msg display on form submit failed
    $msg = '<div class="alert alert-danger col-sm-6 ml-5 mt-2" role="alert"> Unable to Add </div>';
   }
 }
 }
?>
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center rounded-top">
          <h3>Add New Product</h3>
        </div>
        <div class="card-body">
          <form action="" method="POST">
            <div class="mb-3">
              <label for="pname" class="form-label">Product Name</label>
              <input type="text" class="form-control" id="pname" name="pname" placeholder="Enter product name">
            </div>
            <div class="mb-3">
              <label for="pdop" class="form-label">Date of Purchase</label>
              <input type="date" class="form-control" id="pdop" name="pdop">
            </div>
            <div class="mb-3">
              <label for="pava" class="form-label">Available</label>
              <input type="text" class="form-control" id="pava" name="pava" onkeypress="isInputNumber(event)" placeholder="Available quantity">
            </div>
            <div class="mb-3">
              <label for="ptotal" class="form-label">Total</label>
              <input type="text" class="form-control" id="ptotal" name="ptotal" onkeypress="isInputNumber(event)" placeholder="Total quantity">
            </div>
            <div class="mb-3">
              <label for="poriginalcost" class="form-label">Original Cost Each</label>
              <input type="text" class="form-control" id="poriginalcost" name="poriginalcost" onkeypress="isInputNumber(event)" placeholder="Original cost per item">
            </div>
            <div class="mb-3">
              <label for="psellingcost" class="form-label">Selling Cost Each</label>
              <input type="text" class="form-control" id="psellingcost" name="psellingcost" onkeypress="isInputNumber(event)" placeholder="Selling cost per item">
            </div>
            <div class="d-flex justify-content-center gap-2">
              <button type="submit" class="btn btn-success px-4" id="psubmit" name="psubmit">Submit</button>
              <a href="assets.php" class="btn btn-outline-secondary px-4">Close</a>
            </div>
            <?php if(isset($msg)) {echo $msg; } ?>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Only Number for input fields -->
<script>
  function isInputNumber(evt) {
    var ch = String.fromCharCode(evt.which);
    if (!(/[0-9]/.test(ch))) {
      evt.preventDefault();
    }
  }
</script>
<?php
include('includes/footer.php'); 
?>