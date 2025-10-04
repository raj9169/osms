<?php
define('TITLE', 'Work Order');
define('PAGE', 'work');
include('includes/header.php'); 
include('../dbConnection.php');
// session_start();
if(isset($_SESSION['is_adminlogin'])){
  $aEmail = $_SESSION['aEmail'];
} else {
  echo "<script> location.href='login.php'; </script>";
}

// fetch record only if id is present
$row = [];
if(isset($_REQUEST['id'])){
  $id = intval($_REQUEST['id']); // secure input
  $sql = "SELECT * FROM assignwork WHERE request_id = $id";
  $result = $con->query($sql);
  if($result && $result->num_rows > 0){
    $row = $result->fetch_assoc();
  }
}
?>

<style>
/* Hide everything except print-area when printing */
@media print {
  body * {
    visibility: hidden;
  }
  #print-area, #print-area * {
    visibility: visible;
  }
  #print-area {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
  }
}
</style>

<div class="col-sm-8 mt-5 mx-auto">
  <div class="card shadow-lg rounded-3 border-0">
    <div class="card-header bg-primary text-white text-center">
      <h4><i class="fas fa-file-alt me-2"></i> Assigned Work Details</h4>
    </div>
    <div class="card-body" id="print-area">
      <?php if(!empty($row)) { ?>
      <table class="table table-striped table-bordered align-middle">
        <tbody>
          <tr><th>Request ID</th><td><?php echo $row['request_id']; ?></td></tr>
          <tr><th>Request Info</th><td><?php echo $row['request_info']; ?></td></tr>
          <tr><th>Description</th><td><?php echo $row['request_desc']; ?></td></tr>
          <tr><th>Name</th><td><?php echo $row['request_name']; ?></td></tr>
          <tr><th>Address</th>
              <td><?php echo $row['request_add1']." ".$row['request_add2']; ?></td></tr>
          <tr><th>City</th><td><?php echo $row['request_city']; ?></td></tr>
          <tr><th>State</th><td><?php echo $row['request_state']; ?></td></tr>
          <tr><th>Pin Code</th><td><?php echo $row['request_zip']; ?></td></tr>
          <tr><th>Email</th><td><?php echo $row['request_email']; ?></td></tr>
          <tr><th>Mobile</th><td><?php echo $row['request_mobile']; ?></td></tr>
          <tr><th>Assigned Date</th><td><?php echo $row['request_date']; ?></td></tr>
          <tr><th>Technician</th><td><?php echo $row['request_tech']; ?></td></tr>
          <tr><th>Customer Sign</th><td style="height:60px;"></td></tr>
          <tr><th>Technician Sign</th><td style="height:60px;"></td></tr>
        </tbody>
      </table>
      <?php } else { ?>
        <div class="alert alert-warning text-center">🚫 No assigned work found for this Request ID.</div>
      <?php } ?>
    </div>

    <?php if(!empty($row)) { ?>
    <div class="card-footer text-center d-print-none">
      <button class="btn btn-danger me-3" onclick="window.print()">
        <i class="fas fa-print me-1"></i> Print
      </button>
      <a href="work.php" class="btn btn-secondary">
        <i class="fas fa-times me-1"></i> Close
      </a>
    </div>
    <?php } ?>
  </div>
</div>

<?php include('includes/footer.php'); ?>
