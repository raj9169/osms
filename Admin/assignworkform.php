<?php
define('TITLE', 'Assign Work Order');
define('PAGE', 'request');
include('includes/header.php'); 
include('../dbConnection.php');

if (session_id() == '') {
  session_start();
}
if (!isset($_SESSION['is_adminlogin'])) {
  echo "<script> location.href='login.php'; </script>";
  exit;
}

// Fetch request data
if (isset($_REQUEST['view'])) {
  $sql = "SELECT * FROM submitrequest WHERE request_id = {$_REQUEST['id']}";
  $result = $con->query($sql);
  $row = $result->fetch_assoc();
}

// Assign work
if (isset($_REQUEST['assign'])) {
  if (empty($_REQUEST['request_id']) || empty($_REQUEST['assigntech']) || empty($_REQUEST['inputdate'])) {
    $msg = '<div class="alert alert-warning mt-3" role="alert">Fill All Fields</div>';
  } else {
    $rid   = $_REQUEST['request_id'];
    $rinfo = $_REQUEST['request_info'];
    $rdesc = $_REQUEST['requestdesc'];
    $rname = $_REQUEST['requestername'];
    $radd1 = $_REQUEST['address1'];
    $radd2 = $_REQUEST['address2'];
    $rcity = $_REQUEST['requestercity'];
    $rstate= $_REQUEST['requesterstate'];
    $rzip  = $_REQUEST['requesterzip'];
    $remail= $_REQUEST['requesteremail'];
    $rmobile=$_REQUEST['requestermobile'];
    $rassigntech = $_REQUEST['assigntech'];
    $rdate = $_REQUEST['inputdate'];

    $sql = "INSERT INTO assignwork 
            (request_id, request_info, request_desc, request_name, request_add1, request_add2, request_city, request_state, request_zip, request_email, request_mobile, request_tech, request_date)
            VALUES 
            ('$rid','$rinfo','$rdesc','$rname','$radd1','$radd2','$rcity','$rstate','$rzip','$remail','$rmobile','$rassigntech','$rdate')";

    if ($con->query($sql) === TRUE) {
      // delete from submitrequest once assigned
      $con->query("DELETE FROM submitrequest WHERE request_id = $rid");

      // Redirect to viewassignwork.php with same request_id
      header("Location: viewassignwork.php?view=1&id=$rid");
      exit;
    } else {
      $msg = '<div class="alert alert-danger mt-3" role="alert">Unable to Assign Work</div>';
    }
  }
}
?>

<div class="col-sm-9 col-md-10 mt-5">
  <h3 class="text-primary mb-4"><i class="fas fa-tasks me-2"></i>Assign Work Order</h3>

  <div class="card shadow">
    <div class="card-body">
      <form action="" method="POST">
        <div class="mb-3">
          <label class="form-label">Request ID</label>
          <input type="text" class="form-control" name="request_id" value="<?php if(isset($row['request_id'])) {echo $row['request_id']; }?>" readonly>
        </div>
        <div class="mb-3">
          <label class="form-label">Request Info</label>
          <input type="text" class="form-control" name="request_info" value="<?php if(isset($row['request_info'])) {echo $row['request_info']; }?>" readonly>
        </div>
        <div class="mb-3">
          <label class="form-label">Description</label>
          <input type="text" class="form-control" name="requestdesc" value="<?php if(isset($row['request_desc'])) {echo $row['request_desc']; }?>" readonly>
        </div>
        <div class="mb-3">
          <label class="form-label">Requester Name</label>
          <input type="text" class="form-control" name="requestername" value="<?php if(isset($row['request_name'])) {echo $row['request_name']; }?>" readonly>
        </div>

        <!-- Address -->
        <div class="row">
          <div class="col-md-6 mb-3">
            <input type="text" class="form-control" name="address1" value="<?php if(isset($row['request_add1'])) {echo $row['request_add1']; }?>" readonly placeholder="Address Line 1">
          </div>
          <div class="col-md-6 mb-3">
            <input type="text" class="form-control" name="address2" value="<?php if(isset($row['request_add2'])) {echo $row['request_add2']; }?>" readonly placeholder="Address Line 2">
          </div>
        </div>

        <!-- City / State / Zip -->
        <div class="row">
          <div class="col-md-4 mb-3">
            <input type="text" class="form-control" name="requestercity" value="<?php if(isset($row['request_city'])) {echo $row['request_city']; }?>" readonly placeholder="City">
          </div>
          <div class="col-md-4 mb-3">
            <input type="text" class="form-control" name="requesterstate" value="<?php if(isset($row['request_state'])) {echo $row['request_state']; }?>" readonly placeholder="State">
          </div>
          <div class="col-md-4 mb-3">
            <input type="text" class="form-control" name="requesterzip" value="<?php if(isset($row['request_zip'])) {echo $row['request_zip']; }?>" readonly placeholder="Zip">
          </div>
        </div>

        <!-- Email / Mobile -->
        <div class="row">
          <div class="col-md-8 mb-3">
            <input type="email" class="form-control" name="requesteremail" value="<?php if(isset($row['request_email'])) {echo $row['request_email']; }?>" readonly placeholder="Email">
          </div>
          <div class="col-md-4 mb-3">
            <input type="text" class="form-control" name="requestermobile" value="<?php if(isset($row['request_mobile'])) {echo $row['request_mobile']; }?>" readonly placeholder="Mobile">
          </div>
        </div>

        <!-- Technician Dropdown -->
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Assign Technician</label>
            <select class="form-select" name="assigntech" required>
              <option value="">Select Technician</option>
              <?php
              $techSql = "SELECT empName FROM technician";
              $techResult = $con->query($techSql);
              if ($techResult->num_rows > 0) {
                while ($tech = $techResult->fetch_assoc()) {
                  echo "<option value='{$tech['empName']}'>{$tech['empName']}</option>";
                }
              }
              ?>
            </select>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Assign Date</label>
            <input type="date" class="form-control" name="inputdate" required>
          </div>
        </div>

        <div class="text-end">
          <button type="submit" class="btn btn-success" name="assign">Assign</button>
          <a href="request.php" class="btn btn-secondary">Back</a>
        </div>
      </form>
      <?php if(isset($msg)) {echo $msg;} ?>
    </div>
  </div>
</div>

<?php include('includes/footer.php'); ?>
