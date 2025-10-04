<?php
// Start session and check login
session_start();
if (!isset($_SESSION['is_login'])) {
    echo "<script>location.href='../login.php'</script>";
}
$rEmail = $_SESSION['rEmail'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <title><?php echo TITLE ?></title>

    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background-color: #f8f9fa;
            padding-top: 60px;
            transition: all 0.3s;
            z-index: 999;
        }
        .sidebar .nav-link {
            color: #495057;
            font-weight: 500;
            margin: 5px 0;
        }
        .sidebar .nav-link.active {
            background-color: #fd590dde;
            color: #fff;
            border-radius: 5px;
        }
        /* Collapsed sidebar for small screens */
        .sidebar.show { left: 0; }
        .overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 998;
        }
        .overlay.show { display: block; }
        @media (max-width: 768px) {
            .sidebar { left: -250px; }
            .main-content { margin-left: 0 !important; }
        }
        /* Main content */
        .main-content { margin-left: 250px; transition: all 0.3s; padding: 20px; }
    </style>
</head>
<body>

<!-- Top navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top shadow">
    <div class="container-fluid">
        <button class="btn btn-primary me-2" id="sidebarToggle"><i class="fas fa-bars"></i></button>
        <a class="navbar-brand" href="UserProfile.php">OSMS</a>
    </div>
</nav>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <ul class="nav flex-column px-2">
        <li class="nav-item mt-3">
            <a href="UserProfile.php" class="nav-link <?php if(PAGE=='UserProfile'){echo 'active';} ?>">
                <i class="fas fa-user me-2"></i> Profile
            </a>
        </li>
        <li class="nav-item">
            <a href="SubmitRequest.php" class="nav-link <?php if(PAGE=='SubmitRequest'){echo 'active';} ?>">
                <i class="fas fa-file-alt me-2"></i> Submit Request
            </a>
        </li>
        <li class="nav-item">
            <a href="ServiceStatus.php" class="nav-link <?php if(PAGE=='ServiceStatus'){echo 'active';} ?>">
                <i class="fas fa-tasks me-2"></i> Service Status
            </a>
        </li>
        <li class="nav-item">
            <a href="ChangePassword.php" class="nav-link <?php if(PAGE=='ChangePassword'){echo 'active';} ?>">
                <i class="fas fa-key me-2"></i> Change Password
            </a>
        </li>
        <li class="nav-item mt-3">
            <a href="../logout.php" class="nav-link text-danger">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
        </li>
    </ul>
</div>

<!-- Overlay for mobile -->
<div class="overlay" id="overlay"></div>

<!-- Main content -->
<div class="main-content">
<!-- JS Scripts at the end of body -->
<script>
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('show');
        overlay.classList.toggle('show');
    });

    overlay.addEventListener('click', () => {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
    });
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
