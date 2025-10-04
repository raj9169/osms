<?php
define('TITLE', 'Requesters');
define('PAGE', 'requesters');
include('includes/header.php'); 
include('../dbConnection.php');

if(isset($_SESSION['is_adminlogin'])){
    $aEmail = $_SESSION['aEmail'];
} else {
    echo "<script> location.href='login.php'; </script>";
}
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-dark">List of Requesters</h3>
        <a class="btn btn-primary btn-lg" href="insertreq.php">
            <i class="fas fa-plus"></i> Add Requester
        </a>
    </div>

    <?php
    $sql = "SELECT * FROM userlogin";
    $result = $con->query($sql);

    if($result->num_rows > 0){
        echo '<div class="table-responsive shadow-sm rounded">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Requester ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>';
        while($row = $result->fetch_assoc()){
            echo '<tr>
                <th scope="row">'.$row["r_login_id"].'</th>
                <td>'.$row["r_name"].'</td>
                <td>'.$row["r_email"].'</td>
                <td class="text-center">
                    <form action="editreq.php" method="POST" class="d-inline">
                        <input type="hidden" name="id" value="'.$row["r_login_id"].'">
                        <button type="submit" class="btn btn-sm btn-info" name="view" title="Edit">
                            <i class="fas fa-pen"></i>
                        </button>
                    </form>
                    <form action="" method="POST" class="d-inline ms-2">
                        <input type="hidden" name="id" value="'.$row["r_login_id"].'">
                        <button type="submit" class="btn btn-sm btn-danger" name="delete" title="Delete" onclick="return confirm(\'Are you sure to delete this requester?\');">
                            <i class="far fa-trash-alt"></i>
                        </button>
                    </form>
                </td>
            </tr>';
        }
        echo '</tbody>
            </table>
        </div>';
    } else {
        echo '<div class="alert alert-warning text-center">No Requesters Found</div>';
    }

    // Delete requester
    if(isset($_REQUEST['delete'])){
        $sql = "DELETE FROM userlogin WHERE r_login_id = {$_REQUEST['id']}";
        if($con->query($sql) === TRUE){
            echo '<meta http-equiv="refresh" content="0;URL=?deleted" />';
        } else {
            echo '<div class="alert alert-danger text-center">Unable to Delete Data</div>';
        }
    }
    ?>
</div>

<?php
include('includes/footer.php'); 
?>
