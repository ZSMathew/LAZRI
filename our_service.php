<!DOCTYPE html>
<html lang="sw">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lazri Company - Our Services</title>

  <style>
:root {
  --blue: #0b66ff;
  --dark-blue: #053a9b;
  --gray: #f3f4f6;
  --muted: rgb(63, 63, 63);
  --white: #ffffff;
  --shadow: 0 4px 10px rgba(0,0,0,0.1);
  --radius: 12px;
}

* { box-sizing: border-box; margin: 0; padding: 0; }
body {
  font-family: "Segoe UI", Arial, sans-serif;
  background: #f8fbff;
  color: #0f1724;
  line-height: 1.6;
}

.container { max-width: 99%; margin: auto; padding: 20px; }
section { scroll-margin-top: 80px; }

/* ====== HEADER ====== */
header {
  background: var(--dark-blue);
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px 24px;
  box-shadow: var(--shadow);
  position: sticky;
  top: 0;
  z-index: 1000;
}
header img { height: 50px; }
nav ul { display: flex; gap: 20px; list-style: none; }
nav ul li { position: relative; }
nav ul li a {
  position: relative;
  text-decoration: none;
  color: var(--white);
  font-weight: 500;
  padding: 8px 12px;
  transition: color 0.3s;
}
nav ul li a::after {
  content: "";
  position: absolute;
  left: 0;
  bottom: 0;
  height: 2px;
  width: 0%;
  background: var(--white);
  transition: width 0.3s ease;
}
nav ul li a:hover { color: var(--muted); }
nav ul li a:hover::after { width: 100%; }
nav ul li a.active::after { width: 100%; }

/* ====== HERO ====== */
.hero {
  display: grid;
  grid-template-columns: repeat(auto-fit,minmax(300px,1fr));
  gap: 30px;
  align-items: center;
  padding: 40px 0;
  animation: fadeIn 1.2s ease-in-out;
}
.hero-left h2 { font-size: 28px; color: var(--dark-blue); margin-bottom: 12px; }
.hero-left p { color: var(--muted); margin-bottom: 20px; }
.hero-actions { display: flex; gap: 12px; flex-wrap: wrap; }
.btn {
  padding: 10px 16px;
  border-radius: 10px;
  cursor: pointer;
  font-weight: 600;
  border: none;
  transition: transform 0.3s, background 0.3s;
}
.btn-primary { background: var(--blue); color: var(--white); }
.btn-primary:hover { background: var(--dark-blue); transform: scale(1.05); }
.hero-image img {
  width: 100%;
  max-height: 350px;
  border-radius: var(--radius);
  object-fit: cover;
  box-shadow: var(--shadow);
  animation: zoomIn 2s ease-in-out;
}

/* ====== SERVICES ====== */
.services {
  display: grid;
  grid-template-columns: repeat(auto-fit,minmax(280px,1fr));
  gap: 20px;
  margin-top: 30px;
}
.card {
  background: var(--white);
  border-radius: var(--radius);
  box-shadow: var(--shadow);
  padding: 16px;
  transition: transform 0.3s, box-shadow 0.3s;
  opacity: 0;
  animation: slideUp 1s ease forwards;
}
.card:nth-child(1){animation-delay:0.3s;}
.card:nth-child(2){animation-delay:0.6s;}
.card:nth-child(3){animation-delay:0.9s;}
.card:nth-child(4){animation-delay:1.2s;}
.card:hover {
  transform: translateY(-8px) scale(1.02);
  box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}
.card img {
  width: 100%;
  height: 160px;
  object-fit: cover;
  border-radius: var(--radius);
  margin-bottom: 12px;
}
.card h3 { color: var(--dark-blue); margin-bottom: 8px; }
.card p, .card ul { font-size: 14px; color: var(--muted); }

ul{ margin-left: 2rem; }

/* ====== FOOTER ====== */
footer {
  background: var(--muted);
  color: var(--white);
  text-align: center;
  padding: 20px;
  margin-top: 40px;
  animation: fadeIn 1.5s ease-in;
}

/* ====== FORM INPUTS ====== */
.form-group { position: relative; margin-bottom: 16px; }
.form-group input,
.form-group textarea,
.styled-select {
  width: 100%;
  padding: 12px;
  border: 1px solid #ddd;
  border-radius: 10px;
  font-size: 15px;
  background: var(--white);
  transition: 0.3s;
}
.form-group input:hover,
.form-group textarea:hover,
.styled-select:hover {
  border-color: var(--blue);
  background: #fff;
  box-shadow: 0 0 6px var(--dark-blue);
}
.form-group input:focus,
.form-group textarea:focus,
.styled-select:focus {
  outline: none;
  border-color: var(--dark-blue);
  box-shadow: 0 0 8px var(--dark-blue);
}
.form-group label {
  position: absolute;
  top: 50%;
  left: 12px;
  transform: translateY(-50%);
  color: var(--muted);
  font-size: 15px;
  pointer-events: none;
  transition: 0.3s ease;
  background: #f9fafe;
  padding: 0 6px;
}
.form-group input:focus + label,
.form-group input:not(:placeholder-shown) + label,
.form-group textarea:focus + label,
.form-group textarea:not(:placeholder-shown) + label {
  top: -8px;
  left: 10px;
  font-size: 12px;
  color: var(--blue);
}

/* ====== POPUP MODAL ====== */
.modal {
  display: none;
  position: fixed;
  z-index: 2000;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0,0,0,0.5);
}
.modal-content {
  background: var(--gray);
  margin: 5% auto;
  padding: 20px;
  border-radius: var(--radius);
  width: 90%;
  max-width: 500px;
  box-shadow: var(--shadow);
  animation: fadeIn 0.3s ease-in-out;
}
.close {
  color: var(--muted);
  float: right;
  font-size: 28px;
  font-weight: bold;
  cursor: pointer;
}
.close:hover { color: var(--dark-blue); }

/* ====== ANIMATIONS ====== */
@keyframes fadeIn {
  from {opacity: 0; transform: translateY(20px);}
  to {opacity: 1; transform: translateY(0);}
}
@keyframes slideUp {
  from {opacity:0; transform: translateY(40px);}
  to {opacity:1; transform: translateY(0);}
}
@keyframes zoomIn {
  from {transform: scale(0.9); opacity:0;}
  to {transform: scale(1); opacity:1;}
}
  </style>
</head>
<body>
<?php
// ======= DB CONNECTION =======
$host = "localhost"; 
$user = "root";      // weka user wa db
$pass = "";          // password ya db
$db   = "LAZRI";  

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die("DB connection failed: " . $conn->connect_error);
}

$successMsg = "";

// ======= HANDLE FORM SUBMIT =======
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $fullname = $conn->real_escape_string($_POST['fullname']);
  $email    = $conn->real_escape_string($_POST['email']);
  $phone    = $conn->real_escape_string($_POST['phone']);
  $service  = $conn->real_escape_string($_POST['services']);
  $others   = $conn->real_escape_string($_POST['otherservice']);
  $details  = $conn->real_escape_string($_POST['details']);

$sql = "INSERT INTO orders (fullname,email,phone,service,otherservice,details) 
        VALUES ('$fullname','$email','$phone','$service','$others','$details')";

  if ($conn->query($sql) === TRUE) {
    $successMsg = "✅ Your request has been sent successfully!";
  } else {
    $successMsg = "❌ Error: " . $conn->error;
  }
}
?>

  <!-- Header -->
  <header>
    <h1><img src="./images/Logo2.png" alt="Lazri Logo"></h1>
    <nav>
      <ul>
        <li><a href="index.html"><b>Home</b></a></li>
        <li><a href="our service.html" class="active"><b>Our Services</b></a></li>
        <li><a href="Project.html"><b>Our Projects</b></a></li>
        <li><a href="About.html"><b>About Us</b></a></li>
        <li><a href="contact.html"><b>Contact Us</b></a></li>
      </ul>
    </nav>
  </header>

  <!-- Hero -->
  <main class="container">
    <section class="hero">
      <div class="hero-left">
        <h2>We serve you digitally — Security, Quality, Innovation, & Implementation.</h2>
        <p>LAZRI is a company established in 2025 by a team of professionals; we provide Website Development & Design, ICT consultancy & Maintenance, CCTV installation, and multimedia solutions to grow your business in a secure and modern way.</p>
        <div class="hero-actions">
          <button class="btn btn-primary" onclick="scrollToSection('huduma')">Check out Services</button>
          <a href="faq.html"><button class="btn btn-primary">Ask Any Question & FAQ</button></a>
          <button class="btn btn-primary" onclick="openModal()">Place order</button>
        </div>
      </div>
      <div class="hero-image">
        <img id="slideshow" src="./images/serv.jpg" alt="Our Services">
      </div>
    </section>

    <!-- Services -->
    <section id="huduma">
      <h2>Services We Provide</h2>
      <p class="muted">Our services are focused on the market and the needs of modern customers.</p>
      <div class="services">
        <div class="card">
          <img src="./images/ict.jpg" alt="ICT">
          <h3>ICT Services & Consultancy</h3>
          <p>We provide IT consulting, systems development, network management, cloud solutions, and Software & Hardware Maintenance and repair.</p>
          <ul>
            <li>Development & Integration</li>
            <li>Network Design & Maintenance</li>
            <li>Software & Hardware Maintenance</li>
          </ul>
        </div>
        <div class="card">
          <img src="./images/serv.jpg" alt="Web Design">
          <h3>Web Design & Systems Development</h3>
          <p>We provide modern services for designing and developing websites and systems that grow with your business.</p>
          <ul>
            <li>Systems Development</li>
            <li>Website Design & Maintenance</li>
            <li>System Rebuilding</li>
          </ul>
        </div>
        <div class="card">
          <img src="./images/cctv.jpg" alt="CCTV">
          <h3>CCTV Camera Installation</h3>
          <p>Installation of CCTV systems for your homes and businesses for security and other security systems.</p>
          <ul>
            <li>Design & Site Survey</li>
            <li>24/7 Monitoring Options</li>
            <li>Integration with Alarm Systems</li>
          </ul>
        </div>
        <div class="card">
          <img src="./images/malt.jpg" alt="Multimedia">
          <h3>Multimedia & Media Solutions</h3>
          <p>We provide modern services for posters, banners, video production, live streaming, and event AV setup services for better communication.</p>
          <ul>
            <li>Video Production</li>
            <li>Live Streaming</li>
            <li>Event AV Setup</li>
          </ul>
        </div>
      </div>
    </section>
  </main>

  <!-- Popup Modal -->
  <div id="orderModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal()">&times;</span>
      <h2>Place your order now</h2>
      <p class="muted">Send us your project details, we will respond as soon as possible.</p><br>
      <form>
        <div class="form-group">
          <input type="text" placeholder=" " required>
          <label>Your Full Name</label>
        </div>

        <div class="form-group">
          <input type="email" placeholder=" " required>
          <label>Email</label>
        </div>

        <div class="form-group">
          <input type="text" placeholder=" " required>
          <label>Phone Number</label>
        </div>

        <select id="services" name="services" required class="styled-select">
          <option value="" disabled selected>~ Select service ~</option>
          <optgroup label="ICT Services & Consultancy">
            <option value="consult">Consultancy</option>
            <option value="network">Networking</option>
            <option value="software">Software Solutions</option>
          </optgroup>
          <optgroup label="Web Design & Development">
            <option value="webdev">Website Development</option>
            <option value="hosting">Hosting</option>
            <option value="seo">SEO Optimization</option>
          </optgroup>
          <optgroup label="CCTV & Security">
            <option value="install">Installation</option>
            <option value="maintain">Maintenance</option>
            <option value="monitoring">24/7 Monitoring</option>
          </optgroup>
          <optgroup label="Multimedia Solutions">
            <option value="design">Graphic Design</option>
            <option value="video">Video Production</option>
            <option value="stream">Live Streaming</option>
          </optgroup>
          <optgroup label="Other Services">
            <option value="training">Training</option>
            <option value="repair">Hardware Repair</option>
            <option value="custom">Custom Project</option>
          </optgroup>
        </select><br><br>

        <div class="form-group">
          <input type="text" placeholder=" (option)">
          <label>Others Service You may need</label>
        </div>

        <div class="form-group">
          <textarea rows="5" placeholder=" " required></textarea>
          <label>Details of the services you need...</label>
        </div>

        <button class="btn btn-primary" type="submit">Send a Request</button>
      </form>
    </div>
  </div>

  <!-- Footer -->
  <footer>
    <p>&copy; 2025 Lazri Company Limited. All rights reserved.</p>
  </footer>

  <script>
    function scrollToSection(id){
      document.getElementById(id).scrollIntoView({behavior:'smooth'});
    }

    // slide images
    const images = [
      "./images/serv.jpg",
      "./images/campany.jpg",
      "./images/company5.jpg",
      "./images/company3.jpg"
    ];
    let index = 0; 
    const slide = document.getElementById("slideshow");
    function changeImage() {
      index = (index + 1) % images.length;
      slide.src = images[index];
    }
    setInterval(changeImage, 4000);

    // Modal Functions
    function openModal() {
      document.getElementById("orderModal").style.display = "block";
    }
    function closeModal() {
      document.getElementById("orderModal").style.display = "none";
    }
    window.onclick = function(event) {
      let modal = document.getElementById("orderModal");
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }
  </script>
</body>
</html>
