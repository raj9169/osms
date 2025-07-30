<?php
define('TITLE','Status');
define('PAGE','ServiceStatus');
include('includes/header.php');
include('../dbConnection.php');
session_start();
if($_SESSION['is_login']){
   $rEmail=$_SESSION['rEmail'];
}else{
   echo "<script>location.href='login.php'</script>";
}
?>
 
 <!--Start 2nd col--->
 <div class="col-sm-6 mt-5 ml-5">
   <form action="" method="POST" class="mt-5 form-inline d-print-none">
      <div class="form-group mr-3">
         <label for="checkid">Enter Requester ID :</label>
         <input type="text" class="form-control ml-3" name="checkid">
      </div>
      <button type="submit" class="btn btn-primary" name="rClick">Search</button>
   </form>

   <?php
    if(isset($_POST['rClick'])) {
      $rId = trim($_POST['checkid']);
      if(empty($rId)) {
        echo '<div class="alert alert-warning mt-2" role="alert">Please enter a Requester ID.</div>';
    } else
      {
        $rId=mysqli_real_escape_string($con,trim($_REQUEST['checkid']));
         $sql="SELECT *FROM assignwork WHERE request_id=$rId";
         $result=$con->query($sql);
         if($result && $result->num_rows == 1){
         $row=$result->fetch_assoc();
         if($row['request_id'] == $rId){
    ?>
    <h3 class="text-center mt-5">Assigned Work Details</h3>
     <table class="table table-bordered">
       <tbody>
        <tr>
           <td>Request ID</td>
           <td><?php if(isset($row['request_id'])){echo $row['request_id'];} ?></td>
        </tr>
        <tr>
           <td>Request Info</td>
           <td><?php if(isset($row['request_info'])){echo $row['request_info'];} ?></td>
        </tr>
        <td>Request Description</td>
           <td><?php if(isset($row['request_desc'])){echo $row['request_desc'];} ?></td>
        </tr>
        <td>Name</td>
           <td><?php if(isset($row['request_name'])){echo $row['request_name'];} ?></td>
        </tr>
        <td>Address Line 1</td>
           <td><?php if(isset($row['request_add1'])){echo $row['request_add1'];} ?></td>
        </tr>
        <td>Address Line 1</td>
           <td><?php if(isset($row['request_add2'])){echo $row['request_add2'];} ?></td>
        </tr>
        </tr>
        <td>City</td>
           <td><?php if(isset($row['request_city'])){echo $row['request_city'];} ?></td>
        </tr>
        <td>State</td>
           <td><?php if(isset($row['request_state'])){echo $row['request_state'];} ?></td>
        </tr>
        <td>Pin Code</td>
           <td><?php if(isset($row['request_zip'])){echo $row['request_zip'];} ?></td>
        </tr>
        <td>Email</td>
           <td><?php if(isset($row['request_email'])){echo $row['request_email'];} ?></td>
        </tr>
        <td>Mobile Number</td>
           <td><?php if(isset($row['request_mobile'])){echo $row['request_mobile'];} ?></td>
        </tr>
        <td>Assign Date</td>
           <td><?php if(isset($row['request_date'])){echo $row['request_date'];} ?></td>
        </tr>
        <td>Technician Name</td>
           <td><?php if(isset($row['request_tech'])){echo $row['request_tech'];} ?></td>
        </tr>
        <td>Customer Sign</td>
           <td></td>
        </tr>
        <td>Technician Sign</td>
           <td></td>
        </tr>
       </tbody>
       </table>
         <div class="text-center">
           <form action="" class="d-print-none">
             <input type="submit" class="btn btn-primary" value="Print" onclick="window.print()">
             <input type="submit" class="btn btn-secondary" value="Close">
           </form>
         </div>
       <?php }}else{
           echo '<div class="alert alert-warning mt-2" role="alert"> Your Request is Still Pending </div>';
       }
     } 
   } 
     ?>
 </div>   <!---End 2nd col--->


<?php include('includes/footer.php'); ?>