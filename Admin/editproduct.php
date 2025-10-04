<?php    
define('TITLE', 'Update Product');
define('PAGE', 'assets');
include('includes/header.php'); 
include('../dbConnection.php');

// session_start();
if(isset($_SESSION['is_adminlogin'])){
  $aEmail = $_SESSION['aEmail'];
} else {
  echo "<script> location.href='login.php'; </script>";
}

// update
// update
if(isset($_REQUEST['pupdate'])){
  if(($_REQUEST['pname'] == "") || ($_REQUEST['pdop'] == "") || ($_REQUEST['pava'] == "") || ($_REQUEST['ptotal'] == "") || ($_REQUEST['poriginalcost'] == "") || ($_REQUEST['psellingcost'] == "")){
    $msg = '<div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
              ⚠️ Please fill all fields.
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
  } else {
    $pid = $_REQUEST['pid'];
    $pname = $_REQUEST['pname'];
    $pdop = $_REQUEST['pdop'];
    $pava = $_REQUEST['pava'];
    $ptotal = $_REQUEST['ptotal'];
    $poriginalcost = $_REQUEST['poriginalcost'];
    $psellingcost = $_REQUEST['psellingcost'];

    $sql = "UPDATE assets 
            SET pname = '$pname', pdop = '$pdop', pava = '$pava', ptotal = '$ptotal', 
                poriginalcost = '$poriginalcost', psellingcost = '$psellingcost' 
            WHERE pid = '$pid'";
    if($con->query($sql) === TRUE){
      echo "<script>
              alert('✅ Product updated successfully!');
              window.location.href = 'assets.php';
            </script>";
      exit;
    } else {
      $msg = '<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                ❌ Unable to update. Please try again.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    }
  }
}


// fetch product for editing
if(isset($_REQUEST['view'])){
  $sql = "SELECT * FROM assets WHERE pid = {$_REQUEST['id']}";
  $result = $con->query($sql);
  $row = $result->fetch_assoc();
}
?>

<div class="col-md-8 mx-auto mt-5">
  <div class="card shadow-lg border-0 rounded-3">
    <div class="card-header bg-primary text-white text-center">
      <h4><i class="fas fa-edit me-2"></i>Update Product Details</h4>
    </div>
    <div class="card-body">
      <form action="" method="POST">
        <div class="mb-3">
          <label for="pid" class="form-label">Product ID</label>
          <input type="text" class="form-control" id="pid" name="pid" 
                 value="<?php if(isset($row['pid'])) {echo $row['pid']; }?>" readonly>
        </div>

        <div class="mb-3">
          <label for="pname" class="form-label">Product Name</label>
          <input type="text" class="form-control" id="pname" name="pname" 
                 value="<?php if(isset($row['pname'])) {echo $row['pname']; }?>">
        </div>

        <div class="mb-3">
          <label for="pdop" class="form-label">Date of Purchase</label>
          <input type="date" class="form-control" id="pdop" name="pdop" 
                 value="<?php if(isset($row['pdop'])) {echo $row['pdop']; }?>">
        </div>

        <div class="mb-3">
          <label for="pava" class="form-label">Available Quantity</label>
          <input type="text" class="form-control" id="pava" name="pava" 
                 value="<?php if(isset($row['pava'])) {echo $row['pava']; }?>" 
                 onkeypress="isInputNumber(event)">
        </div>

        <div class="mb-3">
          <label for="ptotal" class="form-label">Total Quantity</label>
          <input type="text" class="form-control" id="ptotal" name="ptotal" 
                 value="<?php if(isset($row['ptotal'])) {echo $row['ptotal']; }?>" 
                 onkeypress="isInputNumber(event)">
        </div>

        <div class="mb-3">
          <label for="poriginalcost" class="form-label">Original Cost (Each)</label>
          <input type="text" class="form-control" id="poriginalcost" name="poriginalcost" 
                 value="<?php if(isset($row['poriginalcost'])) {echo $row['poriginalcost']; }?>" 
                 onkeypress="isInputNumber(event)">
        </div>

        <div class="mb-3">
          <label for="psellingcost" class="form-label">Selling Price (Each)</label>
          <input type="text" class="form-control" id="psellingcost" name="psellingcost" 
                 value="<?php if(isset($row['psellingcost'])) {echo $row['psellingcost']; }?>" 
                 onkeypress="isInputNumber(event)">
        </div>

        <div class="text-center">
          <button type="submit" class="btn btn-success me-2" id="pupdate" name="pupdate">
            <i class="fas fa-save me-1"></i> Update
          </button>
          <a href="assets.php" class="btn btn-secondary">
            <i class="fas fa-times me-1"></i> Cancel
          </a>
        </div>
        <?php if(isset($msg)) { echo $msg; } ?>
      </form>
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

<?php include('includes/footer.php'); ?>
