<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>OSMS - Online Service Management System</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Roboto', sans-serif;
      scroll-behavior: smooth;
    }

    .navbar {
      transition: background-color 0.3s ease;
    }

    .navbar-scrolled {
      background-color: #0d6efd !important;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .navbar-brand {
      font-weight: bold;
    }

    .mainheading {
      padding: 120px 0;
      text-align: center;
      background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('images/image_home.jpeg') no-repeat center center/cover;
      color: #fff;
    }

    .mainheading h1 {
      font-weight: 700;
      font-size: 3rem;
    }

    .mainheading p {
      font-size: 1.2rem;
    }

    .card img {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
    }

    .card {
      border-radius: 1rem;
      transition: transform 0.3s ease;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .btn-lg {
      padding: 10px 30px;
      border-radius: 30px;
    }

    .section-title {
      font-weight: 600;
      font-size: 2rem;
      margin-bottom: 30px;
    }

    footer a {
      color: #fff;
      margin-right: 10px;
      transition: color 0.3s ease;
    }

    footer a:hover {
      color: #0dcaf0;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-transparent">
  <div class="container">
    <a class="navbar-brand" href="#">OSMS</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse text-center" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#service">Services</a></li>
        <li class="nav-item"><a class="nav-link" href="User/UserRegistration.php">Registration</a></li>
        <li class="nav-item"><a class="nav-link" href="User/login.php">Login</a></li>
        <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Hero -->
<header class="mainheading" id="home">
  <div class="container">
    <h1>Welcome to OSMS</h1>
    <p>Your satisfaction is our priority</p>
    <a href="User/login.php" class="btn btn-success btn-lg me-2">Login</a>
    <a href="User/UserRegistration.php" class="btn btn-outline-light btn-lg">Signup</a>
  </div>
</header>

<!-- Services -->
<section id="service" class="py-5 bg-light text-center">
  <div class="container">
    <h2 class="section-title">Our Services</h2>
    <div class="row g-4">
      <div class="col-md-4">
        <a href="User/login.php"><i class="fas fa-tv fa-4x text-success mb-3"></i></a>
        <h5>Electronic Appliances</h5>
      </div>
      <div class="col-md-4">
        <a href="User/login.php"><i class="fas fa-sliders-h fa-4x text-primary mb-3"></i></a>
        <h5>Preventive Maintenance</h5>
      </div>
      <div class="col-md-4">
        <a href="User/login.php"><i class="fas fa-cogs fa-4x text-info mb-3"></i></a>
        <h5>Fault Repair</h5>
      </div>
    </div>
  </div>
</section>

<!-- Testimonials -->
<section class="py-5 bg-danger text-white">
  <div class="container">
    <h2 class="section-title text-white text-center">Happy Customers</h2>
    <div class="row g-4">
      <?php
        $testimonials = [
          ["name" => "Sushil Kumar", "img" => "images/sushil.jpg"],
          ["name" => "Pooja Kumari", "img" => "images/avtar2.jpeg"],
          ["name" => "Pankaj Kumar", "img" => "images/avtar3.jpeg"],
          ["name" => "Kalpana Kumari", "img" => "images/avtar4.jpeg"]
        ];
        foreach ($testimonials as $cust) {
          echo '<div class="col-lg-3 col-md-6">
                  <div class="card text-dark shadow-sm text-center p-3">
                    <img src="' . $cust["img"] . '" alt="avatar" class="img-fluid mx-auto mb-3" />
                    <h5>' . $cust["name"] . '</h5>
                    <p>Great platform! Very satisfied with the TV repair services and customer support provided.</p>
                  </div>
                </div>';
        }
      ?>
    </div>
  </div>
</section>

<!-- Contact -->
<section id="contact" class="py-5">
  <div class="container">
    <h2 class="section-title text-center">Contact Us</h2>
    <div class="row">
      <div class="col-md-8">
        <?php include('ContactForm.php'); ?>
      </div>
      <div class="col-md-4">
        <h5>Head Office</h5>
        <p>OSMS Pvt Ltd<br>Boring Road, Patna<br>Bihar - 800013<br>Phone: 7488472585</p>
        
        <hr>
        <h5>Branch Office</h5>
        <p>Udyog Vihar, Gurgaon<br>Haryana - 122016<br>Phone: 7488472585</p>
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
<footer class="bg-dark text-white text-center py-4">
  <div class="container d-flex justify-content-between align-items-center flex-wrap">
    <div>
      <span>Follow Us:</span>
      <a href="#"><i class="fab fa-facebook-f ms-2"></i></a>
      <a href="#"><i class="fab fa-twitter ms-2"></i></a>
      <a href="#"><i class="fab fa-youtube ms-2"></i></a>
      <a href="#"><i class="fab fa-google-plus-g ms-2"></i></a>
      <a href="#"><i class="fas fa-rss ms-2"></i></a>
    </div>
    <div>
      <small>Designed by Shankar &copy; 2025</small>
      <a href="Admin/adminlogin.php" class="ms-2 text-white">Admin Login</a>
    </div>
  </div>
</footer>

<!-- Scroll Navbar Behavior -->
<script>
  window.addEventListener('scroll', function () {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 50) {
      navbar.classList.add('navbar-scrolled');
    } else {
      navbar.classList.remove('navbar-scrolled');
    }
  });
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
