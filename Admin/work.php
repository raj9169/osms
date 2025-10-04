<?php
define('TITLE', 'Work Order');
define('PAGE', 'work');
include('includes/header.php'); 
include('../dbConnection.php');
//session_start();
if(isset($_SESSION['is_adminlogin'])){
  $aEmail = $_SESSION['aEmail'];
} else {
  echo "<script> location.href='login.php'; </script>";
}
?>
<div class="col-sm-9 col-md-10 mt-5">
  <div class="card shadow-lg rounded-3 border-0">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h4 class="mb-0"><i class="fas fa-tasks me-2"></i> Assigned Work Orders</h4>
    </div>
    <div class="card-body">
      <?php 
      $sql = "SELECT * FROM assignwork";
      $result = $con->query($sql);
      if($result->num_rows > 0){
        echo '<div class="table-responsive">
        <table class="table table-hover align-middle text-center">
          <thead class="table-dark">
            <tr>
              <th>Req ID</th>
              <th>Request Info</th>
              <th>Name</th>
              <th>Address</th>
              <th>City</th>
              <th>Mobile</th>
              <th>Technician</th>
              <th>Assigned Date</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>';
        while($row = $result->fetch_assoc()){
          echo '<tr>
            <td><span class="badge bg-secondary">'.$row["request_id"].'</span></td>
            <td>'.$row["request_info"].'</td>
            <td>'.$row["request_name"].'</td>
            <td>'.$row["request_add2"].'</td>
            <td>'.$row["request_city"].'</td>
            <td>'.$row["request_mobile"].'</td>
            <td><span class="badge bg-info text-dark">'.$row["request_tech"].'</span></td>
            <td><span class="badge bg-success">'.$row["request_date"].'</span></td>
            <td>
              <form action="viewassignwork.php" method="POST" class="d-inline">
                <input type="hidden" name="id" value='. $row["request_id"] .'>
                <button type="submit" class="btn btn-sm btn-outline-primary" name="view" value="View">
                  <i class="far fa-eye"></i>
                </button>
              </form>
              <form action="" method="POST" class="d-inline">
                <input type="hidden" name="id" value='. $row["request_id"] .'>
                <button type="submit" class="btn btn-sm btn-outline-danger" name="delete" value="Delete" 
                onclick="return confirm(\'Are you sure you want to delete this record?\');">
                  <i class="far fa-trash-alt"></i>
                </button>
              </form>
            </td>
          </tr>';
        }
        echo '</tbody></table></div>';
      } else {
        echo '<div class="alert alert-warning text-center">🚫 No Work Orders Found</div>';
      }

      if(isset($_REQUEST['delete'])){
        $sql = "DELETE FROM assignwork WHERE request_id = {$_REQUEST['id']}";
        if($con->query($sql) === TRUE){
          echo '<meta http-equiv="refresh" content= "0;URL=?deleted" />';
        } else {
          echo '<div class="alert alert-danger">❌ Unable to Delete Data</div>';
        }
      }
      ?>
    </div>
  </div>
</div>
</div>
</div>
<?php
include('includes/footer.php'); 
?>
