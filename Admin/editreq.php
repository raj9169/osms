<?php    
define('TITLE', 'Update Requester');
define('PAGE', 'requesters');
include('includes/header.php'); 
include('../dbConnection.php');

if(isset($_SESSION['is_adminlogin'])){
    $aEmail = $_SESSION['aEmail'];
} else {
    echo "<script> location.href='login.php'; </script>";
}

// Update requester
if(isset($_REQUEST['requpdate'])){
    if(empty($_REQUEST['r_login_id']) || empty($_REQUEST['r_name']) || empty($_REQUEST['r_email'])){
        echo "<script>alert('Please fill all fields!');</script>";
    } else {
        $rid = $_REQUEST['r_login_id'];
        $rname = $_REQUEST['r_name'];
        $remail = $_REQUEST['r_email'];

        $sql = "UPDATE userlogin SET r_name = '$rname', r_email = '$remail' WHERE r_login_id = '$rid'";
        if($con->query($sql) === TRUE){
            // Simple alert and redirect
            echo "<script>
                    alert('Requester updated successfully!');
                    window.location.href='requester.php';
                  </script>";
            exit;
        } else {
            echo "<script>alert('Unable to update requester.');</script>";
        }
    }
}

// Fetch requester details if "view" is set
if(isset($_REQUEST['view'])){
    $sql = "SELECT * FROM userlogin WHERE r_login_id = {$_REQUEST['id']}";
    $result = $con->query($sql);
    $row = $result->fetch_assoc();
}
?>

<div class="container mt-5">
    <div class="card shadow-sm mx-auto" style="max-width: 600px;">
        <div class="card-header bg-primary text-white text-center">
            <h4 class="mb-0">Update Requester Details</h4>
        </div>
        <div class="card-body">
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="r_login_id" class="form-label">Requester ID</label>
                    <input type="text" class="form-control" id="r_login_id" name="r_login_id" value="<?php echo $row['r_login_id'] ?? ''; ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="r_name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="r_name" name="r_name" value="<?php echo $row['r_name'] ?? ''; ?>">
                </div>
                <div class="mb-3">
                    <label for="r_email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="r_email" name="r_email" value="<?php echo $row['r_email'] ?? ''; ?>">
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-success" id="requpdate" name="requpdate">
                        <i class="fas fa-save"></i> Update
                    </button>
                    <a href="requester.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include('includes/footer.php'); 
?>
