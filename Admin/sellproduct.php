<?php    
define('TITLE', 'Sell Product');
define('PAGE', 'assets');
include('includes/header.php'); 
include('../dbConnection.php');

if(isset($_SESSION['is_adminlogin'])){
    $aEmail = $_SESSION['aEmail'];
} else {
    echo "<script> location.href='login.php'; </script>";
}

if(isset($_REQUEST['psubmit'])){
    $pava = $_REQUEST['pava']; // Available quantity
    $quantity = $_REQUEST['pquantity'];

    if(empty($_REQUEST['cname']) || empty($_REQUEST['cadd']) || empty($_REQUEST['pname']) || empty($quantity) || empty($_REQUEST['psellingcost']) || empty($_REQUEST['totalcost']) || empty($_REQUEST['selldate'])){
        $msg = '<div class="alert alert-warning mt-3" role="alert">Please fill all fields!</div>';
    } elseif($quantity > $pava) {
        $msg = '<div class="alert alert-danger mt-3" role="alert">Requested quantity exceeds available stock!</div>';
    } else {
        $pid = $_REQUEST['pid'];
        $new_pava = $pava - $quantity;

        $custname = $_REQUEST['cname'];
        $custadd = $_REQUEST['cadd'];
        $cpname = $_REQUEST['pname'];
        $cpeach = $_REQUEST['psellingcost'];
        $cptotal = $_REQUEST['totalcost'];
        $cpdate = $_REQUEST['selldate'];

        // Insert into customer
        $sqlin = "INSERT INTO customer(custname, custadd, cpname, cpquantity, cpeach, cptotal, cpdate) 
                  VALUES ('$custname','$custadd', '$cpname', '$quantity', '$cpeach', '$cptotal', '$cpdate')";

        if($con->query($sqlin) === TRUE){
            // Update available quantity in assets
            $sql = "UPDATE assets SET pava = '$new_pava' WHERE pid = '$pid'";
            $con->query($sql);

            // Redirect with success message
            echo "<script>
                alert('Product sold successfully!');
                window.location.href='assets.php';
            </script>";
            exit;
        }
    }
}

if(isset($_REQUEST['issue'])){
    $sql = "SELECT * FROM assets WHERE pid = {$_REQUEST['id']}";
    $result = $con->query($sql);
    $row = $result->fetch_assoc();
}
?>

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-danger text-white text-center">
            <h3>Customer Bill</h3>
        </div>
        <div class="card-body">
            <form action="" method="POST" id="sellForm">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="pid" class="form-label">Product ID</label>
                        <input type="text" class="form-control" id="pid" name="pid" value="<?= isset($row['pid']) ? $row['pid'] : '' ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="pname" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="pname" name="pname" value="<?= isset($row['pname']) ? $row['pname'] : '' ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="cname" class="form-label">Customer Name</label>
                        <input type="text" class="form-control" id="cname" name="cname">
                    </div>
                    <div class="col-md-6">
                        <label for="cadd" class="form-label">Customer Address</label>
                        <input type="text" class="form-control" id="cadd" name="cadd">
                    </div>
                    <div class="col-md-4">
                        <label for="pava" class="form-label">Available</label>
                        <input type="text" class="form-control" id="pava" name="pava" value="<?= isset($row['pava']) ? $row['pava'] : '' ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="pquantity" class="form-label">Quantity</label>
                        <input type="text" class="form-control" id="pquantity" name="pquantity" oninput="calculateTotal()" onkeypress="isInputNumber(event)">
                    </div>
                    <div class="col-md-4">
                        <label for="psellingcost" class="form-label">Price Each</label>
                        <input type="text" class="form-control" id="psellingcost" name="psellingcost" value="<?= isset($row['psellingcost']) ? $row['psellingcost'] : '' ?>" oninput="calculateTotal()" onkeypress="isInputNumber(event)">
                    </div>
                    <div class="col-md-6">
                        <label for="totalcost" class="form-label">Total Price</label>
                        <input type="text" class="form-control" id="totalcost" name="totalcost" readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="inputDate" class="form-label">Date</label>
                        <input type="date" class="form-control" id="inputDate" name="selldate">
                    </div>
                </div>
                <div class="mt-4 text-center">
                    <button type="submit" class="btn btn-danger me-2" name="psubmit">Submit</button>
                    <a href="assets.php" class="btn btn-secondary">Close</a>
                </div>
                <?php if(isset($msg)) echo $msg; ?>
            </form>
        </div>
    </div>
</div>

<script>
function isInputNumber(evt) {
    var ch = String.fromCharCode(evt.which);
    if (!(/[0-9]/.test(ch))) {
        evt.preventDefault();
    }
}

// Real-time total calculation
function calculateTotal() {
    let quantity = parseFloat(document.getElementById('pquantity').value) || 0;
    let price = parseFloat(document.getElementById('psellingcost').value) || 0;
    document.getElementById('totalcost').value = (quantity * price).toFixed(2);

    // Check if quantity exceeds available
    let available = parseFloat(document.getElementById('pava').value) || 0;
    if(quantity > available){
        document.getElementById('totalcost').value = '';
        alert('Requested quantity exceeds available stock!');
        document.getElementById('pquantity').value = '';
    }
}
</script>

<?php include('includes/footer.php'); ?>
