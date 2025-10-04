<?php
define('TITLE', 'Success');
define('PAGE', 'Receipt');
include('includes/header.php');
include('../dbConnection.php');
//session_start();

if (!isset($_SESSION['is_login'])) {
    echo "<script>location.href='login.php'</script>";
    exit;
}

$rEmail = $_SESSION['rEmail'];

$sql = "SELECT * FROM submitrequest WHERE request_id = {$_SESSION['myid']}";
$result = $con->query($sql);

?>

<div class="container my-5">
    <?php if ($result->num_rows == 1): 
        $row = $result->fetch_assoc(); ?>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Request Receipt</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped mb-4">
                            <tbody>
                                <tr>
                                    <th>User ID</th>
                                    <td><?= htmlspecialchars($row['request_id']) ?></td>
                                </tr>
                                <tr>
                                    <th>Name</th>
                                    <td><?= htmlspecialchars($row['request_name']) ?></td>
                                </tr>
                                <tr>
                                    <th>Email ID</th>
                                    <td><?= htmlspecialchars($row['request_email']) ?></td>
                                </tr>
                                <tr>
                                    <th>Requester Info</th>
                                    <td><?= htmlspecialchars($row['request_info']) ?></td>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <td><?= htmlspecialchars($row['request_desc']) ?></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-between">
                            <a href="submitrequest.php" class="btn btn-secondary">Back</a>
                            <button class="btn btn-danger" onclick="window.print()">Print</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-danger text-center">
            Failed to load the request details.
        </div>
    <?php endif; ?>
</div>

<?php include('includes/footer.php'); ?>
