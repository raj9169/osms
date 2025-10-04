<?php
define('TITLE', 'Technician');
define('PAGE', 'technician');
include('includes/header.php'); 
include('../dbConnection.php');

if (isset($_SESSION['is_adminlogin'])) {
  $aEmail = $_SESSION['aEmail'];
} else {
  echo "<script> location.href='login.php'; </script>";
}
?>

<div class="col-sm-9 col-md-10 mt-5">
  <div class="card shadow-lg">
    <div class="card-header bg-dark text-white">
      <h4 class="mb-0"><i class="fas fa-users-cog"></i> List of Technicians</h4>
    </div>
    <div class="card-body">
      <?php
        $sql = "SELECT * FROM technician";
        $result = $con->query($sql);

        if ($result->num_rows > 0) {
          echo '<div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
              <thead class="table-dark">
                <tr>
                  <th scope="col">Emp ID</th>
                  <th scope="col">Name</th>
                  <th scope="col">City</th>
                  <th scope="col">Mobile</th>
                  <th scope="col">Email</th>
                  <th scope="col" class="text-center">Action</th>
                </tr>
              </thead>
              <tbody>';
          
          while ($row = $result->fetch_assoc()) {
            echo '<tr>
              <th scope="row">'.$row["empid"].'</th>
              <td>'.$row["empName"].'</td>
              <td>'.$row["empCity"].'</td>
              <td>'.$row["empMobile"].'</td>
              <td>'.$row["empEmail"].'</td>
              <td class="text-center">
                <form action="editemp.php" method="POST" class="d-inline">
                  <input type="hidden" name="id" value="'.$row["empid"].'">
                  <button type="submit" class="btn btn-sm btn-primary" name="view" value="View">
                    <i class="fas fa-edit"></i>
                  </button>
                </form>
                <form action="" method="POST" class="d-inline">
                  <input type="hidden" name="id" value="'.$row["empid"].'">
                  <button type="submit" class="btn btn-sm btn-danger" name="delete" value="Delete" onclick="return confirm(\'Are you sure you want to delete this technician?\');">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </form>
              </td>
            </tr>';
          }

          echo '</tbody></table></div>';
        } else {
          echo "<div class='alert alert-info text-center'>No Technicians Found</div>";
        }

        if (isset($_REQUEST['delete'])) {
          $sql = "DELETE FROM technician WHERE empid = {$_REQUEST['id']}";
          if ($con->query($sql) === TRUE) {
            echo '<meta http-equiv="refresh" content="0;URL=?deleted" />';
          } else {
            echo "<div class='alert alert-danger'>Unable to Delete Data</div>";
          }
        }
      ?>
    </div>
  </div>

  <!-- Floating Add Button -->
  <div class="mt-3 text-end">
    <a class="btn btn-success shadow" href="insertemp.php">
      <i class="fas fa-plus"></i> Add Technician
    </a>
  </div>
</div>

<?php
include('includes/footer.php'); 
?>
