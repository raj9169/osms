<?php 
define('TITLE','Requester Submit');
define('PAGE','SubmitRequest');
include('includes/header.php');
include('../dbConnection.php');
session_start();
if($_SESSION['is_login']){
   $rEmail=$_SESSION['rEmail'];
}else{
   echo "<script>location.href='login.php'</script>";
}

if(isset($_REQUEST['submitrequest'])){
  if(($_REQUEST['nameinfo']=="") || ($_REQUEST['requestdesc']=="") || ($_REQUEST['requestername']=="") ||
   ($_REQUEST['requesteradd1']=="") || ($_REQUEST['requesteradd2']=="") || ($_REQUEST['requestercity']=="") || 
   ($_REQUEST['requesterstate']=="") || ($_REQUEST['requesterzip']=="") || ($_REQUEST['requesteremail']=="") ||
    ($_REQUEST['requestermobile']=="") || ($_REQUEST['requesterdate']=="") ){
       $regmsg= '<div class="alert alert-success mt-2" role="alert">All Fields Are Required</div>';
    }else{
      $rinfo=$_REQUEST["nameinfo"];
      $rdesc=$_REQUEST["requestdesc"];
      $rname=$_REQUEST["requestername"];
      $radd1=$_REQUEST["requesteradd1"];
      $radd2=$_REQUEST["requesteradd2"];
      $rcity=$_REQUEST["requestercity"];
      $rstate=$_REQUEST["requesterstate"];
      $rzip=$_REQUEST["requesterzip"];
      $remail=$_REQUEST["requesteremail"];
      $rmobile=$_REQUEST["requestermobile"];
      $rdate=$_REQUEST["requesterdate"];
      $sql="INSERT INTO submitrequest(request_info,request_desc,request_name,request_add1,request_add2,request_city,request_state,request_zip,request_email,request_mobile,request_date) VALUES('$rinfo','$rdesc','$rname','$radd1','$radd2','$rcity','$rstate','$rzip','$remail','$rmobile','$rdate')";
        if($con->query($sql)== TRUE){
          $genid=mysqli_insert_id($con);
            $regmsg= '<div class="alert alert-success mt-2" role="alert">Account successfully created</div>';
            $_SESSION['myid']=$genid;
            echo "<script>location.href='SubmitRequestSuccess.php'</script>";
        }else{
          $regmsg= '<div class="alert alert-danger mt-2" role="alert">Unable to  create Account</div>';
          }         

      }
}
 ?>
<!---Start Service Request form 2nd col--->
<div class="col-sm-9 col-md-101 mt-5">  <!---Start service Request form 2nd col-->
<form action="" method="POST" class="mt-5 mx-5">
  <div class="form-group">
    <label for="inputRequestInfo">Request Info</label>
    <input type="text" class="form-control" id="inputRequestInfo" placeholder="Request Info" name="nameinfo">
  </div>
  <div class="form-group">
     <label for="inputRequestDescription">Description</label>
      <input type="text" class="form-control" id="inputRequestDescription" placeholder="Write Description" name="requestdesc">
  </div>
  <div class="form-group">
  <label for="inputName">Name</label>
     <input type="text" class="form-control" id="inputName" placeholder="Shankar" name="requestername">
  </div>
   <div class="form-row">
      <div class="form-group col-md-6">
      <label for="inputAddress">Address Line1</label>
        <input type="text" class="form-control" id="inputAddress" placeholder="House No. 266" name="requesteradd1">
      </div>
       <div class="form-group col-md-6">
      <label for="inputAddress2">Address Line2</label>
        <input type="text" class="form-control" id="inputAddress2" placeholder="Patliputra Colony" name="requesteradd2">
      </div>
   </div>
    <div class="form-row">
     <div class="form-group col-md-6">
       <label for="inputCity">City</label>
        <input type="text" class="form-control" id="inputCity" name="requestercity">
      </div>
       <div class="form-group col-md-4">
       <label for="inputState">State</label>
        <input type="text" class="form-control" id="inputState" name="requesterstate">
      </div>
       <div class="form-group col-md-2">
       <label for="inputZip">Zip</label>
        <input type="text" class="form-control" id="inputZip" name="requesterzip" onkeypress="isInputNumber(event)">
      </div>
   </div>
   <div class="form-row">
     <div class="form-group col-md-6">
       <label for="inputEmail">Email</label>
         <input type="email" class="form-control" id="inputEmail" name="requesteremail">
     </div>
     <div class="form-group col-md-2">
       <label for="inputMobile">Mobile</label>
         <input type="text" class="form-control" id="inputMobile" name="requestermobile" onkeypress="isInputNumber(event)">
     </div>
     <div class="form-group col-md-3">
       <label for="inputDate">Date</label>
         <input type="date" class="form-control" id="inputDate" name="requesterdate">
     </div>
   </div>
   <button type="submit" class="btn btn-primary" name="submitrequest">Submit</button>
   <button type="reset" class="btn btn-primary" name="resetrequest">Reset</button>
</form>
<?php if(isset($regmsg)){echo $regmsg;} ?>
</div>   <!---End service Request form 2nd col-->

<!---Only number for input--->
<script>
function isInputNumber(evt){
  var ch= String.fromCharCode(evt.which);
  if(!(/[0-9]/.test(ch))){
    evt.preventDefault();
  }
}
</script>
<?php include('includes/footer.php'); ?>