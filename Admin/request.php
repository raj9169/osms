<?php
define('TITLE', 'Requests');
define('PAGE', 'request');
include('includes/header.php'); 
include('../dbConnection.php');

if(session_id() == '') {
  session_start();
}
if(!isset($_SESSION['is_adminlogin'])){
  echo "<script> location.href='login.php'; </script>";
}
?>

<div class="col-sm-9 col-md-10 mt-5">
  <h2 class="fw-bold mb-4 text-primary"><i class="fas fa-envelope-open-text me-2"></i>Pending Requests</h2>

  <?php 
  $sql = "SELECT * FROM submitrequest";
  $result = $con->query($sql);
  if($result->num_rows > 0){
  ?>
    <div class="table-responsive">
      <table class="table table-hover shadow-sm border">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Info</th>
            <th>Description</th>
            <th>Date</th>
            <th class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = $result->fetch_assoc()){ ?>
            <tr>
              <td>#<?php echo $row['request_id']; ?></td>
              <td><?php echo $row['request_info']; ?></td>
              <td><?php echo $row['request_desc']; ?></td>
              <td><?php echo $row['request_date']; ?></td>
              <td class="text-center">
                <!-- Redirect to assignworkform.php -->
                <form action="assignworkform.php" method="POST" class="d-inline">
                  <input type="hidden" name="id" value="<?php echo $row['request_id']; ?>">
                  <button type="submit" name="view" class="btn btn-sm btn-outline-success">
                    <i class="fas fa-user-cog me-1"></i> Assign
                  </button>
                </form>

                <!-- Close Request -->
                <form action="" method="POST" class="d-inline">
                  <input type="hidden" name="id" value="<?php echo $row['request_id']; ?>">
                  <button type="submit" class="btn btn-sm btn-outline-danger" name="close" value="Close">
                    <i class="fas fa-times me-1"></i> Close
                  </button>
                </form>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  <?php
  } else {
    echo '<div class="alert alert-info shadow-sm" role="alert">
            <h5 class="fw-bold"><i class="fas fa-check-circle me-2"></i>No Pending Requests</h5>
            <p>All requests have been assigned.</p>
          </div>';
  }

  // Delete request when "Close" pressed
  if(isset($_REQUEST['close'])){
    $sql = "DELETE FROM submitrequest WHERE request_id = {$_REQUEST['id']}";
    if($con->query($sql) === TRUE){
      echo '<meta http-equiv="refresh" content="0;URL=?closed" />';
    } else {
      echo "<div class='alert alert-danger mt-3'>Unable to Delete Data</div>";
    }
  }
  ?>
</div>

<?php 
include('includes/footer.php'); 
$con->close();
?>
