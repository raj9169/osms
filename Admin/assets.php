<?php
define('TITLE', 'Assests');
define('PAGE', 'assets');
include('includes/header.php');
include('../dbConnection.php'); 
//session_start();
 if(isset($_SESSION['is_adminlogin'])){
  $aEmail = $_SESSION['aEmail'];
 } else {
  echo "<script> location.href='login.php'; </script>";
 }
?>
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-lg-10">
      <div class="card shadow-sm">
        <div class="card-header bg-dark text-white text-center rounded-top">
          <h4>Product/Parts Details</h4>
        </div>
        <div class="card-body">
          <?php
            $sql = "SELECT * FROM assets";
            $result = $con->query($sql);
            if($result->num_rows > 0){
              echo '<div class="table-responsive"><table class="table table-hover align-middle">
                <thead class="table-dark">
                  <tr>
                    <th scope="col">Product ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">DOP</th>
                    <th scope="col">Available</th>
                    <th scope="col">Total</th>
                    <th scope="col">Original Cost Each</th>
                    <th scope="col">Selling Price Each</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>';
              while($row = $result->fetch_assoc()){
                echo '<tr>
                  <th scope="row">'.$row["pid"].'</th>
                  <td>'.$row["pname"].'</td>
                  <td>'.$row["pdop"].'</td>
                  <td>'.$row["pava"].'</td>
                  <td>'.$row["ptotal"].'</td>
                  <td>'.$row["poriginalcost"].'</td>
                  <td>'.$row["psellingcost"].'</td>
                  <td>
                    <form action="editproduct.php" method="POST" class="d-inline">
                      <input type="hidden" name="id" value='. $row["pid"] .'>
                      <button type="submit" class="btn btn-info btn-sm rounded-pill" name="view" value="View" title="Edit">
                        <i class="fas fa-pen"></i>
                      </button>
                    </form>
                    <form action="" method="POST" class="d-inline">
                      <input type="hidden" name="id" value='. $row["pid"] .'>
                      <button type="submit" class="btn btn-secondary btn-sm rounded-pill" name="delete" value="Delete" title="Delete">
                        <i class="far fa-trash-alt"></i>
                      </button>
                    </form>
                    <form action="sellproduct.php" method="POST" class="d-inline">
                      <input type="hidden" name="id" value='. $row["pid"] .'>
                      <button type="submit" class="btn btn-success btn-sm rounded-pill" name="issue" value="Issue" title="Issue">
                        <i class="fas fa-handshake"></i>
                      </button>
                    </form>
                  </td>
                </tr>';
              }
              echo '</tbody></table></div>';
            } else {
              echo '<div class="alert alert-warning text-center">No products found.</div>';
            }
            if(isset($_REQUEST['delete'])){
              $sql = "DELETE FROM assets WHERE pid = {$_REQUEST['id']}";
              if($con->query($sql) === TRUE){
                echo '<meta http-equiv="refresh" content= "0;URL=?deleted" />';
              } else {
                echo '<div class="alert alert-danger">Unable to Delete Data</div>';
              }
            }
          ?>
          <div class="d-flex justify-content-end mt-3">
            <a class="btn btn-danger rounded-pill" href="addproduct.php">
              <i class="fas fa-plus"></i> Add Product
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
include('includes/footer.php'); 
?>