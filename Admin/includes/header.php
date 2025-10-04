<?php
if(!isset($_SESSION)){ session_start(); }
?>
<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <meta http-equiv="X-UA-Compatible" content="ie=edge">
 <title><?php echo TITLE ?></title>

 <!-- Bootstrap 5 CSS -->
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

 <!-- Font Awesome -->
 <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

 <!-- Custom CSS -->
 <link rel="stylesheet" href="../css/custom.css">

 <style>
   body {
     background-color: #f8f9fa;
     font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
   }
   .navbar-brand {
     font-weight: bold;
     font-size: 1.2rem;
   }
   .sidebar {
     min-height: 100vh;
     background: #343a40;
   }
   .sidebar .nav-link {
     color: #ddd;
     margin: 5px 0;
     border-radius: 5px;
     transition: 0.3s;
   }
   .sidebar .nav-link:hover,
   .sidebar .nav-link.active {
     background: #495057;
     color: #fff;
   }
   .content {
     margin-top: 60px;
     padding: 20px;
   }
 </style>
</head>

<body>
 <!-- Top Navbar -->
 <nav class="navbar navbar-dark fixed-top bg-danger shadow">
   <a class="navbar-brand px-3" href="dashboard.php"><i class="fas fa-tools"></i> OSMS</a>
 </nav>

 <!-- Side Bar -->
 <div class="container-fluid">
   <div class="row">
     <nav class="col-md-2 d-none d-md-block sidebar p-3">
       <div class="sidebar-sticky">
         <ul class="nav flex-column">
           <li class="nav-item">
             <a class="nav-link <?php if(PAGE == 'dashboard'){echo 'active';} ?>" href="dashboard.php">
               <i class="fas fa-tachometer-alt"></i> Dashboard
             </a>
           </li>
           <li class="nav-item">
             <a class="nav-link <?php if(PAGE == 'work'){echo 'active';} ?>" href="work.php">
               <i class="fas fa-briefcase"></i> Work Order
             </a>
           </li>
           <li class="nav-item">
             <a class="nav-link <?php if(PAGE == 'request'){echo 'active';} ?>" href="request.php">
               <i class="fas fa-inbox"></i> Requests
             </a>
           </li>
           <li class="nav-item">
             <a class="nav-link <?php if(PAGE == 'assets'){echo 'active';} ?>" href="assets.php">
               <i class="fas fa-box"></i> Assets
             </a>
           </li>
           <li class="nav-item">
             <a class="nav-link <?php if(PAGE == 'technician'){echo 'active';} ?>" href="technician.php">
               <i class="fas fa-user-cog"></i> Technician
             </a>
           </li>
           <li class="nav-item">
             <a class="nav-link <?php if(PAGE == 'requesters'){echo 'active';} ?>" href="requester.php">
               <i class="fas fa-users"></i> Requester
             </a>
           </li>
           <li class="nav-item">
             <a class="nav-link <?php if(PAGE == 'sellreport'){echo 'active';} ?>" href="soldproductreport.php">
               <i class="fas fa-file-invoice-dollar"></i> Sell Report
             </a>
           </li>
           <li class="nav-item">
             <a class="nav-link <?php if(PAGE == 'workreport'){echo 'active';} ?>" href="workreport.php">
               <i class="fas fa-chart-line"></i> Work Report
             </a>
           </li>
           <li class="nav-item">
             <a class="nav-link <?php if(PAGE == 'changepass'){echo 'active';} ?>" href="changepass.php">
               <i class="fas fa-key"></i> Change Password
             </a>
           </li>
           <li class="nav-item">
             <a class="nav-link text-danger" href="../logout.php">
               <i class="fas fa-sign-out-alt"></i> Logout
             </a>
           </li>
         </ul>
       </div>
     </nav>

     <!-- Main Content Start -->
     <main class="col-md-10 ms-sm-auto col-lg-10 content">
       <h2 class="mb-4"><i class="fas fa-gauge-high"></i> <?php echo TITLE; ?></h2>
