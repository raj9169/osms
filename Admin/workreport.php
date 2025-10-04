<?php
define('TITLE', 'Work Report');
define('PAGE', 'workreport');
include('includes/header.php');
include('../dbConnection.php'); 
// session_start();
if(isset($_SESSION['is_adminlogin'])){
    $aEmail = $_SESSION['aEmail'];
} else {
    echo "<script> location.href='login.php'; </script>";
}
?>

<!-- Custom CSS for print -->
<style>
/* Print styles */
@media print {
    body * {
        visibility: hidden;
    }

    /* Only show report */
    #printableReport, #printableReport * {
        visibility: visible;
    }

    #printableReport {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        padding: 20px;
        font-size: 12pt;
    }

    /* Hide print button */
    .d-print-none {
        display: none !important;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    table, th, td {
        border: 1px solid #000;
    }

    th, td {
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }
}
</style>

<div class="col-sm-9 col-md-10 mt-5">
    <!-- Search Form -->
    <div class="card shadow-sm p-4 mb-4">
        <h3 class="text-center mb-4">Work Report</h3>
        <form action="" method="POST" class="row g-3 d-print-none justify-content-center">
            <div class="col-auto">
                <input type="date" class="form-control" id="startdate" name="startdate" placeholder="Start Date" required>
            </div>
            <div class="col-auto align-self-center">
                <span>to</span>
            </div>
            <div class="col-auto">
                <input type="date" class="form-control" id="enddate" name="enddate" placeholder="End Date" required>
            </div>
            <div class="col-auto">
                <input type="submit" class="btn btn-primary" name="searchsubmit" value="Search">
            </div>
        </form>
    </div>

<?php
if(isset($_REQUEST['searchsubmit'])){
    $startdate = $_REQUEST['startdate'];
    $enddate = $_REQUEST['enddate'];

    $sql = "SELECT * FROM assignwork WHERE request_date BETWEEN '$startdate' AND '$enddate'";
    $result = $con->query($sql);

    if($result->num_rows > 0){
        echo '<div class="card shadow-sm p-3" id="printableReport">
            <div class="text-center mb-3">
                <h2>Work Report</h2>
                <p>Date Range: <strong>'.date("d-m-Y", strtotime($startdate)).' to '.date("d-m-Y", strtotime($enddate)).'</strong></p>
                <hr>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
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
                        </tr>
                    </thead>
                    <tbody>';
        while($row = $result->fetch_assoc()){
            echo '<tr>
                    <td>'.$row["request_id"].'</td>
                    <td>'.$row["request_info"].'</td>
                    <td>'.$row["request_name"].'</td>
                    <td>'.$row["request_add2"].'</td>
                    <td>'.$row["request_city"].'</td>
                    <td>'.$row["request_mobile"].'</td>
                    <td>'.$row["request_tech"].'</td>
                    <td>'.$row["request_date"].'</td>
                  </tr>';
        }
        echo '</tbody>
                </table>
            </div>
            <div class="mt-3 d-print-none text-center">
                <button class="btn btn-danger" onclick="window.print()"><i class="bi bi-printer"></i> Print</button>
            </div>
        </div>';
    } else {
        echo "<div class='alert alert-warning col-md-6 mx-auto mt-3 text-center' role='alert'>No Records Found!</div>";
    }
}
?>
</div>

<?php include('includes/footer.php'); ?>
