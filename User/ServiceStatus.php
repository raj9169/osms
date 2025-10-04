<?php
define('TITLE','Status');
define('PAGE','ServiceStatus');
include('includes/header.php');
include('../dbConnection.php');
//session_start();

if($_SESSION['is_login']){
   $rEmail = $_SESSION['rEmail'];
} else {
   echo "<script>location.href='login.php'</script>";
}
?>

<!-- Start Main Content -->
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <!-- Search Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-search"></i> Check Service Status</h5>
                </div>
                <div class="card-body">
                    <form action="" method="POST" class="row g-3 d-print-none">
                        <div class="col-md-8">
                            <label for="checkid" class="form-label">Enter Requester ID</label>
                            <input type="text" class="form-control" name="checkid" placeholder="e.g. 101">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100" name="rClick">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <?php
            if(isset($_POST['rClick'])) {
                $rId = trim($_POST['checkid']);
                if(empty($rId)) {
                    echo '<div class="alert alert-warning mt-2" role="alert">⚠️ Please enter a Requester ID.</div>';
                } else {
                    $rId = mysqli_real_escape_string($con, $rId);

                    // 1️⃣ Check in submitrequest first (pending requests)
                    $sqlSubmit = "SELECT * FROM submitrequest WHERE request_id = $rId";
                    $resultSubmit = $con->query($sqlSubmit);

                    if($resultSubmit && $resultSubmit->num_rows > 0) {
                        echo '<div class="alert alert-warning mt-2" role="alert">⏳ Your request is still pending.</div>';
                    } else {
                        // 2️⃣ Check in assignwork (assigned requests)
                        $sqlAssign = "SELECT * FROM assignwork WHERE request_id = $rId";
                        $resultAssign = $con->query($sqlAssign);

                        if($resultAssign && $resultAssign->num_rows == 1){
                            $row = $resultAssign->fetch_assoc();
                            ?>
                            <!-- Assigned Work Details -->
                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0"><i class="fas fa-tasks"></i> Assigned Work Details</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-hover">
                                        <tbody>
                                            <tr><th>Request ID</th><td><?php echo $row['request_id']; ?></td></tr>
                                            <tr><th>Request Info</th><td><?php echo $row['request_info']; ?></td></tr>
                                            <tr><th>Description</th><td><?php echo $row['request_desc']; ?></td></tr>
                                            <tr><th>Name</th><td><?php echo $row['request_name']; ?></td></tr>
                                            <tr><th>Address Line 1</th><td><?php echo $row['request_add1']; ?></td></tr>
                                            <tr><th>Address Line 2</th><td><?php echo $row['request_add2']; ?></td></tr>
                                            <tr><th>City</th><td><?php echo $row['request_city']; ?></td></tr>
                                            <tr><th>State</th><td><?php echo $row['request_state']; ?></td></tr>
                                            <tr><th>Pin Code</th><td><?php echo $row['request_zip']; ?></td></tr>
                                            <tr><th>Email</th><td><?php echo $row['request_email']; ?></td></tr>
                                            <tr><th>Mobile</th><td><?php echo $row['request_mobile']; ?></td></tr>
                                            <tr><th>Assign Date</th><td><?php echo $row['request_date']; ?></td></tr>
                                            <tr><th>Technician Name</th><td><?php echo $row['request_tech']; ?></td></tr>
                                            <tr><th>Customer Sign</th><td>________________</td></tr>
                                            <tr><th>Technician Sign</th><td>________________</td></tr>
                                        </tbody>
                                    </table>
                                    <div class="text-center d-print-none mt-3">
                                        <button onclick="window.print()" class="btn btn-primary">
                                            <i class="fas fa-print"></i> Print
                                        </button>
                                        <a href="ServiceStatus.php" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> Close
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php
                        } else {
                            // 3️⃣ Not found in both tables
                            echo '<div class="alert alert-danger mt-2" role="alert">❌ Record not found.</div>';
                        }
                    }
                }
            }
            ?>
        </div>
    </div>
</div>
<!-- End Main Content -->

<?php include('includes/footer.php'); ?>
