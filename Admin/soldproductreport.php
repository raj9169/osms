<?php
define('TITLE', 'Product Report');
define('PAGE', 'sellreport');
include('includes/header.php');
include('../dbConnection.php');
// session_start();
if(isset($_SESSION['is_adminlogin'])){
  $aEmail = $_SESSION['aEmail'];
} else {
  echo "<script> location.href='login.php'; </script>";
} 
?>

<div class="container mt-5">
  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
      <h4 class="mb-0">Product Sales Report</h4>
    </div>
    <div class="card-body">
      <form action="" method="POST" class="row g-3 mb-4 d-print-none">
        <div class="col-auto">
          <input type="date" class="form-control" id="startdate" name="startdate" placeholder="Start Date">
        </div>
        <div class="col-auto">
          <input type="date" class="form-control" id="enddate" name="enddate" placeholder="End Date">
        </div>
        <div class="col-auto">
          <button type="submit" class="btn btn-success" name="searchsubmit">
            <i class="bi bi-search"></i> Search
          </button>
        </div>
      </form>

      <?php
      if(isset($_REQUEST['searchsubmit'])){
        $startdate = $_REQUEST['startdate'];
        $enddate = $_REQUEST['enddate'];

        $sql = "SELECT * FROM customer WHERE cpdate BETWEEN '$startdate' AND '$enddate'";
        $result = $con->query($sql);

        if($result->num_rows > 0){
          echo '
          <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
              <thead class="table-dark">
                <tr>
                  <th>Customer ID</th>
                  <th>Name</th>
                  <th>Address</th>
                  <th>Product</th>
                  <th>Quantity</th>
                  <th>Price Each</th>
                  <th>Total</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody>';
          while($row = $result->fetch_assoc()){
            echo '<tr>
              <td>'.$row["custid"].'</td>
              <td>'.$row["custname"].'</td>
              <td>'.$row["custadd"].'</td>
              <td>'.$row["cpname"].'</td>
              <td>'.$row["cpquantity"].'</td>
              <td>'.$row["cpeach"].'</td>
              <td>'.$row["cptotal"].'</td>
              <td>'.$row["cpdate"].'</td>
            </tr>';
          }
          echo '</tbody>
          </table>
          </div>
          <div class="mt-3 d-print-none">
            <button class="btn btn-danger" onclick="window.print()">
              <i class="bi bi-printer"></i> Print Report
            </button>
          </div>';
        } else {
          echo "<div class='alert alert-warning' role='alert'>No Records Found!</div>";
        }
      }
      ?>
    </div>
  </div>
</div>

<?php include('includes/footer.php'); ?>
