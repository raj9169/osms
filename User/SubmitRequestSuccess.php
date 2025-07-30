<?php
define('TITLE','Success');
define('PAGE','Reciept');
include('includes/header.php');
include('../dbConnection.php');
session_start();
if($_SESSION['is_login']){
   $rEmail=$_SESSION['rEmail'];
}else{
   echo "<script>location.href='login.php'</script>";
}
$sql="SELECT *FROM submitrequest WHERE request_id={$_SESSION['myid']}";
$result=$con->query($sql);
if($result->num_rows==1){
    $row=$result->fetch_assoc();
    echo "<div class='col-sm-6 mt-5'><div class='ml-5 mt-5'>
    <table class='table'>
      <tbody>
        <tr>
           <th>User ID</th>
           <td>".$row['request_id']."</td>
        </tr>
        <tr>
           <th>Name</th>
           <td>".$row['request_name']."</td>
        </tr>
        <tr>
           <th>Email ID</th>
           <td>".$row['request_email']."</td>
        </tr>
        <tr>
           <th>Requester Info</th>
           <td>".$row['request_info']."</td>
        </tr>
        <tr>
           <th>Description</th>
           <td>".$row['request_desc']."</td>
        </tr>
        <tr>
           <td><form class='d-print-none'><input type='submit' class='btn btn-danger' value='Print' onClick='window.print()'></form><td>
        </tr>
      </tbody>
    </table>
    </div>";
}else{
    echo "Failed";
}
include('includes/footer.php');
?>