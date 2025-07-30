<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!---Bootstrap css--->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">


     <!-----font awesome----->
     <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
      

    <!---Custom css---->
    <link rel="stylesheet" href="../css/custom.css">

    <title><?php echo TITLE ?></title>
</head>
<body>
    <!----Top navbar--->
    <nav class="navbar navbar-dark fixed-top bg-primary flex-md-nowrap p-0 shadow d-print-none">
    <a href="UserProfile.php" class="navbar-brand col-sm-3 col-md-2 mr-0">OSMS</a></nav>


    <!----start conatiner--->
    <div class="container-fluid">
       <div class="row">   <!--start row--->
          <nav class="col-sm-2 bg-light sidebar py-5 d-print-none">  <!---- start Side bar 1st col start--->
             <div class="sidebar-sticky">
                <ul class="nav flex-column">
                <li class="nav-item mt-5">
                <a  class="nav-link <?php if(PAGE=='UserProfile'){echo 'active';} ?>" href="UserProfile.php" ><i class="fas fa-user"> Profile</i></a>
                </li>
                 <li class="nav-item">
                 <a href="SubmitRequest.php" class="nav-link <?php if(PAGE=='SubmitRequest'){echo 'active';} ?>" ><i class="fab fa-accessible-icon"> Submit Request</i></a>
                 </li>
                 <li class="nav-item">
                 <a href="ServiceStatus.php" class="nav-link <?php if(PAGE=='ServiceStatus'){echo 'active';} ?>"><i class="fas fa-exclamation">  Service Status</i></a>
                 </li>
                 <li class="nav-item">
                 <a href="ChangePassword.php" class="nav-link <?php if(PAGE=='ChangePassword'){echo 'active';} ?>"><i class="fas fa-key">Change Password</i></a>
                 </li>
                 <li class="nav-item">
                 <a href="../logout.php" class="nav-link"><i class="fas fa-sign-out-alt"> Logout</i></a>
                 </li>
                 </ul>
               </div>
          </nav>   <!--end sidebar 1st col-->
         