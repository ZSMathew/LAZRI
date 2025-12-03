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
  --muted: black;
  --white: #ffffff;
  --shadow: 0 4px 10px rgba(0,0,0,0.1);
  --radius: 12px;
  --sidebar-width: 220px;
}

* { 
  box-sizing: border-box;
  margin: 0; 
  padding: 0;
}
body {
  font-family: "Segoe UI", Arial, sans-serif;
  background: #f8fbff;
  color: #0f1724;
  line-height: 1.6;
  overflow-x: hidden;
}

.container {
  max-width: 99%;
  margin: auto;
  padding: 20px;
}
section { 
  scroll-margin-top: 80px; 
}

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
header img { 
  height: 50px; 
}

/* Desktop nav styling (kept visible on larger screens) */
nav ul {
  display: flex;
  gap: 20px;
  list-style: none;
}
nav ul li {
  position: relative; 
}
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
  background: #ffd700;
  transition: width 0.3s ease;
}
nav ul li a:hover {
  color: #ffd700;
}
nav ul li a:hover::after {
  width: 100%; 
}
nav ul li a.active {
  color: #ffd700;
  font-weight: bold;
}
nav ul li a.active::after { 
  width: 100%;
}  

/* Hamburger button (hidden on desktop) */
.menu-toggle {
  display: none;
  font-size: 28px;
  cursor: pointer;
  color: #fff;
}

/* Logo + company name grouping */
.logo-center {
  display: flex;
  align-items: center;
}
.logo-center img {
  height: 45px;
  margin-right: 10px;
}
.company-name {
  font-weight: bold;
  font-size: 16px;
  color: #fff;
}

/* ====== HERO ====== */
.hero {
  display: grid;
  grid-template-columns: repeat(auto-fit,minmax(300px,1fr));
  gap: 30px;
  align-items: center;
  padding: 40px 0;
  animation: fadeIn 1.2s ease-in-out;
}
.hero-left h2 { 
  font-size: 28px;
  color: var(--dark-blue);
  margin-bottom: 12px;
}
.hero-left p {
  color: var(--muted);
  margin-bottom: 20px; 
}
.hero-actions { 
  display: flex;
  gap: 12px; 
  flex-wrap: wrap; 
}
.btn {
  padding: 10px 16px;
  border-radius: 10px;
  cursor: pointer;
  font-weight: 600;
  border: none;
  transition: transform 0.3s, background 0.3s;
}
.btn-primary {
  background: var(--blue); 
  color: var(--white);
}
.btn-primary:hover {
  background: var(--dark-blue);
  transform: scale(1.05);
}
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
.card h3 {
  color: var(--dark-blue);
  margin-bottom: 8px;
}
.card p, .card ul {
  font-size: 14px; 
  color: var(--muted);
}
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
.form-group { 
  position: relative;
  margin-bottom: 16px;
}
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
.close:hover {
  color: var(--dark-blue);
}

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

/* ===== MOBILE / SIDEBAR STYLES ===== */
@media (max-width: 768px) {
  header {
    flex-direction: column;
    padding: 0;
  }

  /* mobile header: everything starts from the far left */
  .mobile-header {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    gap: 12px;
    padding: 10px;
    width: 100%;
    background: var(--dark-blue);
    color: #fff;
    position: relative;
    transition: transform 0.35s cubic-bezier(.2,.9,.3,1);
  }

  .menu-toggle {
    display: block;
    font-size: 28px;
    cursor: pointer;
    color: #fff;
  }

  .logo-center img {
    height: 42px;
    margin-left: 45px;
  }
  .company-name {
    font-size: 15px;
    white-space: nowrap;
    margin-left: 10px;
  }

  /* Sidebar (off-canvas) */
  .sidebar {
    position: fixed;
    left: -70%;
    top: 0;
    height: auto;
    width: 60%;
    max-width: 300px;
    background: var(--dark-blue);
    box-shadow: 4px 0 12px rgba(0, 0, 0, 0.3);
    transition: left 0.32s cubic-bezier(.25,.8,.25,1);
    padding: 70px 0 20px 0;
    border-bottom-right-radius: 12px;
    z-index: 2000;
    overflow-y: auto;
  }

  /* when sidebar is active, slide it in */
  .sidebar.active {
    left: 0;
  }

  /* Ensure the UL inside sidebar is visible when sidebar exists */
  .sidebar ul {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
    padding: 0 20px;
  }


  .sidebar ul li a {
    color: #fff;
    display: block;
    padding: 12px;
    text-decoration: none;
    border-radius: 6px;
    transition: background 0.3s, color 0.3s;
  }

  .sidebar ul li a:hover {
    background: rgba(255, 255, 255, 0.12);
    color: #ffd700;
  }

  .close-btn {
    position: absolute;
    top: 12px;
    right: 15px;
    font-size: 28px;
    cursor: pointer;
    color: #fff;
  }

  /* Hili limebadilika: desktop nav remains hidden visually in mobile flow only if present;
     since your nav is used as the sidebar, we don't force-hide nav ul globally here. */
  /* Slide header to the right when sidebar is open using variable width */
  header.sidebar-open .mobile-header {
    transform: translateX(var(--sidebar-width));
  }
}
/* ===== SUCCESS POPUP ===== */
.popup-message {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background: #72ca7eff;
  padding: 18px 26px;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.25);
  font-size: 16px;
  color: black;
  text-align: center;
  z-index: 3000;
  display: none;
  animation: fadeIn 0.5s ease-out;
}

@keyframes fadeOut {
  from {opacity: 1;}
  to {opacity: 0;}
}

</style>
</head>
<body>
<div id="popup" class="popup-message"></div>

<?php
// ======= DB CONNECTION =======
$host = "localhost"; 
$user = "root";      
$pass = "";          
$db   = "lazri";  

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
if (!empty($successMsg)) {
    echo "
    <script>
        let popup = document.getElementById('popup');
        popup.innerText = '$successMsg';
        popup.style.display = 'block';

        // Popup idumu sekunde 5
        setTimeout(() => {
            popup.style.animation = 'fadeOut 1s forwards';
            setTimeout(() => { popup.style.display = 'none'; }, 1000);
        }, 5000);
    </script>";
}

$conn->close();
?>
  <!-- Header -->
  <header>
    <div class="mobile-header">
      <div class="menu-toggle" id="menu-toggle">☰</div>
      <a href="index.html" class="logo-center">
        <img src="./images/Logo2.png" alt="Lazri Company Logo">
      </a>
      <span class="company-name">LAZRI Company</span>
    </div>

    <nav id="navbar" class="sidebar">
      <span class="close-btn" id="close-btn">&times;</span>
      <ul>
        <li><a href="index.php"><b>Home</b></a></li>
        <li><a href="our_service.php" class="active"><b>Our Services</b></a></li>
        <li><a href="Project.php"><b>Our Projects</b></a></li>
        <li><a href="about.php"><b>About Us</b></a></li>
        <li><a href="contact.php"><b>Contact Us</b></a></li>
      </ul>
    </nav>
  </header>

  <!-- Hero -->
  <main class="container">
    <section class="hero">
      <div class="hero-left">
        <h2>We serve you digitally — Security, Quality, Innovation, & Implementation.</h2>
        <p>LAZRI is a company established in 2025 by a team of professionals; we provide Website Development & Design, 
          ICT consultancy & Maintenance, Digital Security System installation, and Digital Creative Designes to grow your business in a secure and modern way.</p>
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
      <p>Our services are focused on the market and the needs of modern customers.</p>
      <div class="services">
        <div class="card">
          <img src="./images/ict.jpg" alt="ICT">
          <h3>Computer maintenance and repair.</h3>
          <p>Expart computer maintenance and repair we keep your technology runing smoothly. Our service include;</p>
          <ul>
            <li>Software & Hardware Maintenance.</li>
            <li>Virus removal & Protection.</li>
            <li>Data recovery & Backup</li>
            <li>System upgrades & Optimization.</li>
          </ul>
        </div>
        <div class="card">
          <img src="./images/serv.jpg" alt="Web Design">
          <h3>Web design & Development.</h3>
          <p>We provide modern services for designing and developing websites and systems that grow with your business.</p>
          <ul>
            <li>Website design and development.t</li>
            <li>Website updates & maintenance.</li>
            <li>Mobile app design.</li>
            <li>SEO Optimization.</li>
          </ul>
        </div>
        <div class="card">
          <img src="./images/cctv.jpg" alt="CCTV">
          <h3>Digital Security Systems Installation</h3>
          <p>We install state-of-the art digital security solutions to protect your home, business, and other. Our service include;</p>
          <ul>
            <li>CCTV Camera installation & maintenance.</li>
            <li>Access control.</li>
            <li>Gate motor.</li>
            <li>Electric fence Integration with Alarm Systems.</li>
          </ul>
        </div>
        <div class="card">
          <img src="./images/malt.jpg" alt="Multimedia">
          <h3>Creative Digital Design.</h3>
          <p>We provide modern and high quality creative graphics and designing services include.</p>
          <ul>
            <li>Poster and banners design.</li>
            <li>Flyers and card flyers design.</li>
            <li> Business card and events card design</li>
            <li>Printing services (T-shirts, caps, and other materials).</li>
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
<form method="POST" action="">
    <div class="form-group">
      <input type="text" name="fullname" placeholder=" " required>
      <label>Your Full Name</label>
    </div>

    <div class="form-group">
      <input type="email" name="email" placeholder=" " required>
      <label>Email</label>
    </div>

    <div class="form-group">
      <input type="text" name="phone" placeholder=" " required>
      <label>Phone Number</label>
    </div>

    <select id="services" name="services" required class="styled-select">
      <option value="" disabled selected>~ Select service ~</option>

      <optgroup label="Web Design & Development.">
        <option value="Website design and development">Website design and development.</option>
        <option value="Mobile app design">Mobile app design</option>
        <option value="Website hosting">Website hosting</option>
      </optgroup>

      <optgroup label="Digital Security Systems.">
        <option value="CCTV Camera installation & maintenance">CCTV Camera installation & maintenance</option>
        <option value="Access control">Access control</option>
        <option value="Doorbell">Doorbell</option>
        <option value="Electric fence">Electric fence</option>
        <option value="Gate motor">Gate motor</option>
      </optgroup>

      <optgroup label="Digital Creative Design">
        <option value="Poster and banners design">Poster and banners design</option>
        <option value="Flyers and card flyers design">Flyers and card flyers design</option>
        <option value="Business card and events card design">Business card and events card design</option>
        <option value="Printing services">Printing services</option>
        <option value="Logo design">Logo design</option>
      </optgroup>

      <optgroup label="Computer maintenance and repair">
        <option value="Software maintenance & installation">Software maintenance & installation</option>
        <option value="Hardware maintenance and repair">Hardware maintenance and repair</option>
        <option value="System improvements">System improvements</option>
      </optgroup>

    </select>

    <div class="form-group">
      <textarea name="details" placeholder=" " rows="3" required></textarea>
      <label>Describe your project</label>
    </div>

    <button type="submit" class="btn btn-primary">Send Order</button>
</form>
    </div>
  </div>

  <footer>
    <p>© 2025 Lazri Company. All rights reserved.</p>
  </footer>

  <script>
const menuToggle = document.getElementById('menu-toggle');
const sidebar = document.getElementById('navbar');
const closeBtn = document.getElementById('close-btn');
const header = document.querySelector('header');

menuToggle.addEventListener('click', () => {
  sidebar.classList.add('active');
  header.classList.add('sidebar-open');
});

closeBtn.addEventListener('click', () => {
  sidebar.classList.remove('active');
  header.classList.remove('sidebar-open');
});

/* Modal Handling */
function openModal() {
  document.getElementById('orderModal').style.display = 'block';
}
function closeModal() {
  document.getElementById('orderModal').style.display = 'none';
}

/* Scroll to section */
function scrollToSection(id) {
  document.getElementById(id).scrollIntoView({ behavior: 'smooth' });
}

/* Slideshow Animation */
const images = ["./images/serv.jpg", "./images/ict.jpg", "./images/cctv.jpg", "./images/malt.jpg"];
let currentIndex = 0;
setInterval(() => {
  const slideshow = document.getElementById('slideshow');
  currentIndex = (currentIndex + 1) % images.length;
  slideshow.src = images[currentIndex];
}, 3500);
  </script>
</body>
</html>
