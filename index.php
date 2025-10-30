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
      background-color: #11bcefff !important;
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
<!-- <script>
       window.onload = function() {
         alert('Welcome to OSMS - Online Service Management System!');
     }
</script> -->
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
    <a href="./User/login.php" class="btn btn-success btn-lg me-2">Login</a>
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
        <?php include('contactform.php'); ?>
      </div>
      <div class="col-md-4">
        <h5>Head Office</h5>
        <p>Pilani, Jhunjhunu<br>Rajasthan - 333031<br>Phone: 7488472585</p>
        
        <hr>
        <h5>Branch Office</h5>
        <p>Jawahar Nagar, Hyderabad<br>Telangana - 500078<br>Phone: 7488472585</p>
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

 <!-- Chatbot -->
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<div id="chatbot" class="chatbot-container shadow">
  <div class="chatbot-header bg-primary text-white p-2 d-flex justify-content-between align-items-center">
    <span><i class="fas fa-robot me-2"></i>OSMS Chatbot</span>
    <button class="btn-close btn-close-white" onclick="toggleChatbot()"></button>
  </div>

  <div id="chatbot-messages" class="chatbot-messages p-2"></div>

  <!-- Quick Options -->
  <div id="chatbot-options" class="p-2 text-center border-top">
    <button class="btn btn-sm btn-success m-1" onclick="sendAction('service request')">🛠 Service Request</button>
    <button class="btn btn-sm btn-info m-1" onclick="sendAction('status')">📋 Check Status</button>
  </div>

  <div class="chatbot-input d-flex">
    <input id="chatbot-input" type="text" class="form-control" placeholder="Type your message..." 
           onkeypress="if(event.key==='Enter'){sendMessage();}">
    <button class="btn btn-primary ms-1" onclick="sendMessage()"><i class="fas fa-paper-plane"></i></button>
  </div>
</div>

<!-- Floating Button -->
<button id="chatbot-toggle" class="btn btn-primary chatbot-toggle" onclick="toggleChatbot()">
  <i class="fas fa-comments"></i>
</button>

<style>
  /* Chatbot Styles */
  .chatbot-container {
    position: fixed;
    bottom: 80px;
    right: 20px;
    width: 300px;
    height: 400px;
    background: #fff;
    border-radius: 10px;
    display: none;
    flex-direction: column;
    z-index: 2000;
  }
  .chatbot-header {
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
  }
  .chatbot-messages {
    flex: 1;
    overflow-y: auto;
    font-size: 0.9rem;
  }
  .chatbot-messages .msg {
    margin: 5px 0;
    padding: 6px 10px;
    border-radius: 8px;
    max-width: 80%;
    clear: both;
  }
  .msg-user {
    background: #0d6efd;
    color: white;
    margin-left: auto;
  }
  .msg-bot {
    background: #f1f1f1;
    margin-right: auto;
  }
  .chatbot-input {
    border-top: 1px solid #ccc;
    padding: 5px;
  }
  .chatbot-toggle {
    position: fixed;
    bottom: 20px;
    right: 20px;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    z-index: 2000;
  }
</style>

<script>
  // Toggle chatbot window
  function toggleChatbot() {
    const bot = document.getElementById("chatbot");
    bot.style.display = (bot.style.display === "flex") ? "none" : "flex";
    if (bot.style.display === "flex") {
      showWelcome();
    }
  }

  // Show welcome message
  function showWelcome() {
    const container = document.getElementById("chatbot-messages");
    container.innerHTML = "";
    appendMessage("👋 Hi! I’m your OSMS assistant. Please choose an option below:", "bot");
  }

  // Handle quick action buttons (Service Request / Check Status)
  function sendAction(action) {
    const container = document.getElementById("chatbot-messages");
    container.innerHTML = ""; // clear old messages
    appendMessage("👉 " + action, "user");
    sendToBackend(action, 1); // 1 = reset flow
  }

  // Handle text input
  function sendTypedMessage() {
    const input = document.getElementById("chatbot-input");
    const message = input.value.trim();
    if (!message) return;

    if (!validateInput(message)) return;

    appendMessage(message, "user");
    input.value = "";
    sendToBackend(message, 0);
  }

  // Validation rules
  function validateInput(message) {
  const lastBotMessage = getLastBotMessage();
  const trimmed = message.trim();

  if (lastBotMessage.toLowerCase().includes("full name")) {
    if (!/^[a-zA-Z ]{2,}$/.test(trimmed)) {
      appendMessage("⚠️ Please enter a valid name (letters only, at least 2 characters).", "bot");
      return false;
    }
  }

  if (lastBotMessage.toLowerCase().includes("email")) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(trimmed)) {
      appendMessage("⚠️ Please enter a valid email address.", "bot");
      return false;
    }
  }

  if (lastBotMessage.toLowerCase().includes("request id")) {
    if (!/^\d+$/.test(trimmed)) {
      appendMessage("⚠️ Request ID must be a number.", "bot");
      return false;
    }
  }

  return true;
}


  // Get last bot message
  function getLastBotMessage() {
    const msgs = document.querySelectorAll("#chatbot-messages .msg-bot");
    if (msgs.length === 0) return "";
    return msgs[msgs.length - 1].innerText;
  }

  // Send to backend
  async function sendToBackend(message, isAction = 0) {
    try {
      const response = await fetch("chat.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "message=" + encodeURIComponent(message) + "&isAction=" + isAction
      });

      const data = await response.json();
      if (data.reply) {
        appendMessage(data.reply, "bot");
      }
    } catch (err) {
      appendMessage("⚠️ Error contacting server.", "bot");
    }
  }

  // Append message
  function appendMessage(text, sender) {
    const container = document.getElementById("chatbot-messages");
    const msg = document.createElement("div");
    msg.className = "msg " + (sender === "user" ? "msg-user" : "msg-bot");
    msg.innerText = text;
    container.appendChild(msg);
    container.scrollTop = container.scrollHeight;
  }

  // Event listeners
  document.getElementById("chatbot-input").addEventListener("keypress", function (e) {
    if (e.key === "Enter") {
      sendTypedMessage();
    }
  });

  document.getElementById("serviceBtn").addEventListener("click", function () {
    sendAction("service request");
  });

  document.getElementById("statusBtn").addEventListener("click", function () {
    sendAction("status");
  });
</script>




</body>
</html>