<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Lazri Company Limited — Terms & Conditions</title>
  <meta name="description" content="Terms and Conditions for Lazri Companies Limited — Innovate, Connect, Evolve." />
  <style>
    :root{
      --blue:#0b66ff;
      --dark-blue:#053a9b;
      --gray:#f3f4f6;
      --muted: black;
      --max-width:900px;
      --accent:#0b66ff;
      --card-bg:#ffffff;
      --page-bg:#f7f9fc;
      --radius:12px;
      --white:#ffffff;
      --sidebar-width: 220px;
    }
    *{
      box-sizing:border-box;
    }
    body{
      height:100%;
      margin:0;
      font-family: "Segoe UI", Arial, sans-serif;
      background:var(--page-bg);
      color:#0f172a;
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

/* Hamburger button */
.menu-toggle {
  display: none;
  font-size: 28px;
  cursor: pointer;
  color: #fff;
}

/* Logo + company name */
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

/* ===== MOBILE SIDEBAR ===== */
@media (max-width: 768px) {
  header {
    flex-direction: column;
    padding: 0;
  }

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

  .sidebar.active {
    left: 0;
  }

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

  header.sidebar-open .mobile-header {
    transform: translateX(var(--sidebar-width));
  }
}
    .container{
      max-width:var(--max-width);
      margin:36px auto;
      padding:28px;
    }
    
.container {
   max-width: 99%;
    margin: auto;
     padding: 20px; 
    }
section {
   scroll-margin-top: 80px;
   }

    .logo{
      width:68px;
      height:68px;
      background:linear-gradient(135deg,var(--blue),var(--dark-blue));
      border-radius:10px;
      display:flex;
      align-items:center;
      justify-content:center;
      color:white;
      font-weight:700;
      font-size:20px;
    }
    h1{
      font-size:20px;
      margin:0;
    }
    .meta{
      color:var(--muted);
      font-size:13px;
    }
    .card{
      background:var(--card-bg);
      padding:26px;
      border-radius:var(--radius);
      box-shadow:0 6px 18px rgba(11,102,255,0.06);
    }
    .section{
      margin-bottom:18px;
    }
    h2{
      font-size:16px;
      margin:0 0 8px 0;
      color:var(--dark-blue);
    }
    p,li{
      line-height:1.55;
      color:#0f172a;
    }
    ol{
      padding-left:18px;
    }
    .actions{
      display:flex;
      gap:10px;
      align-items:center;
      margin-top:8px;
    }
    .btn{
      background:var(--accent);
      color:#fff;
      padding:10px 14px;
      border-radius:9px;
      border:none;
      cursor:pointer;
      font-weight:600;
    }
    .btn.ghost{
      background:transparent;
      color:var(--accent);
      border:1px solid rgba(11,102,255,0.12);
    }

.back-home {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background: var(--blue);
      color: var(--white);
      padding: 10px 18px;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      text-decoration: none;
      font-weight: bold;
      transition: 0.3s;
    }
    .back-home:hover {
      background: var(--dark-blue);
    }

/* Footer */
footer {
  background: var(--muted);
  color: var(--white);
  text-align: center;
  padding: 20px;
  margin-top: 40px;
}

    @media (max-width:640px){
      .container{
        margin:18px;
        padding:18px;
      }
      .logo{
        width:56px;
        height:56px;}
      }
    /* Printable */
    @media print{
      body{
        background:white;} 
        .actions,.logo,footer{
          display:none;
        } 
        .container{
          box-shadow:none;}
        }
    a.anchor{
      color:var(--accent);
      text-decoration:none;
      margin-left:6px;
      font-size:13px;
    }
  </style>
</head>
<body>
    
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
      <li><a href="our service.php"><b>Our Services</b></a></li>
      <li><a href="Project.php"><b>Our Projects</b></a></li>
      <li><a href="about.php"><b>About Us</b></a></li>
      <li><a href="contact.php"><b>Contact Us</b></a></li>
    </ul>
  </nav>
</header>
  <div class="container">
      <div>
        <h1>Terms & Conditions — Lazri Company Limited</h1>
        <div class="meta">Last updated: September 2025 • Slogan: <strong>Innovate, Connect, Evolve</strong></div>
      </div>

    <main class="card" id="terms-card">
      <div class="section">
        <p>Welcome to Lazri Companies Limited ("Lazri", "Company", "we", "us"). By using our website, products, or services, you agree to these Terms and Conditions. Please read them carefully.</p>
      </div>

      <section id="definitions" class="section">
        <h2>1. Definitions</h2>
        <p>In these Terms:</p>
        <ul>
          <li><strong>"Services"</strong> means the ICT, consultancy, CCTV, multimedia and other solutions provided by Lazri.</li>
          <li><strong>"User"/"Customer"</strong> means an individual, company or entity using our Services.</li>
          <li><strong>"Website"</strong> means our official domain, web pages and digital platforms.</li>
        </ul>
      </section>

      <section id="eligibility" class="section">
        <h2>2. Eligibility</h2>
        <p>You confirm that you are 18 years of age or older, or that you have the legal capacity to enter into a binding agreement. If you do not meet these requirements, please do not use our services.</p>
      </section>

      <section id="use-of-services" class="section">
        <h2>3. Use of Services</h2>
        <ol>
          <li>Services are provided for lawful use only. Any unlawful activity or violation of law is strictly prohibited.</li>
          <li>You may not duplicate, resell, or distribute our services without prior written permission from Lazri.</li>
          <li>You must provide accurate and up-to-date information when registering or requesting services.</li>
        </ol>
      </section>

      <section id="intellectual-property" class="section">
        <h2>4. Intellectual Property</h2>
        <p>All trademarks, logos, slogans (<strong>"Innovate, Connect, Evolve"</strong>), content, and software are the property of Lazri. No part may be copied or used without prior written permission.</p>
      </section>

      <section id="payments" class="section">
        <h2>5. Payments & Fees</h2>
        <ol>
          <li>All services may be subject to fees as agreed. Payments may be due before or after delivery depending on the contract.</li>
          <li>Failure to make timely payments may result in suspension or termination of services.</li>
          <li>Fees are non-refundable unless expressly stated in the service contract.</li>
        </ol>
      </section>

      <section id="privacy" class="section">
        <h2>6. Privacy & Data Protection</h2>
        <p>Lazri respects your privacy. We have implemented security measures to protect client data. Data use is governed by our <a href="/privacy" class="anchor">Privacy Policy</a>.</p>
      </section>

      <section id="liability" class="section">
        <h2>7. Limitation of Liability</h2>
        <p>To the fullest extent permitted by law, Lazri shall not be held liable for indirect, consequential, or special damages arising from the use of our services. Lazri is not responsible for service failures caused by third parties, force majeure events, or user negligence.</p>
      </section>

      <section id="termination" class="section">
        <h2>8. Termination</h2>
        <p>We reserve the right to suspend or terminate services for users who violate these Terms. Users may also terminate services by providing written notice and settling any outstanding payments.</p>
      </section>

      <section id="thirdparties" class="section">
        <h2>9. Third-Party Links & Services</h2>
        <p>Our website may contain links to third-party sites or services. Lazri is not responsible for the content or privacy practices of these external websites.</p>
      </section>

      <section id="governing-law" class="section">
        <h2>10. Governing Law</h2>
        <p>These Terms shall be governed by and construed under the laws of the <strong>United Republic of Tanzania</strong>. The courts of Tanzania shall have jurisdiction over any disputes.</p>
      </section>

      <section id="changes" class="section">
        <h2>11. Changes to Terms</h2>
        <p>We may update these Terms and Conditions from time to time. Changes take effect immediately upon posting on the website. It is the user's responsibility to review the Terms before using our services.</p>
      </section>

      <section id="contact" class="section">
        <h2>12. Contact Information</h2>
        <p>For any questions regarding these Terms, please contact us:</p>
        <ul>
          <li>Email: <a href="mailto:info@lazri.co.tz">info@lazri.co.tz</a></li>
          <li>Website: <a href="https://www.lazri.co.tz">www.lazri.co.tz</a></li>
          <li>Address: Dar es Salaam, Tanzania (for official correspondence)</li>
        </ul>
      </section>

      <div class="actions">
        <button class="btn" onclick="window.print()">Print / Save as PDF</button>
        <button class="btn ghost" onclick="scrollToTop()">Back to Top</button>
      </div>

    </main>
  </div>

  <!-- Footer -->
  <footer>
    <p style="color: white;">&copy; 2025 Lazri Company Limited. All rights reserved.</p>
  </footer>

  <script>
    function scrollToTop(){window.scrollTo({top:0,behavior:'smooth'})}
    // Smooth anchor behaviour
    document.querySelectorAll('a[href^=\"#\"]').forEach(a=>{
      a.addEventListener('click',function(e){e.preventDefault();document.querySelector(this.getAttribute('href')).scrollIntoView({behavior:'smooth'})})
    })

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
  </script>
<a href="index.html" class="back-home">⬅ Back to Home</a>
</body>
</html>
