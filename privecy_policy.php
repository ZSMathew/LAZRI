<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - Lazri Companies Limited</title>
    <style>
         :root {
            --blue: #0b66ff;
            --dark-blue: #053a9b;
            --gray: #f3f4f6;
            --muted: black;
            --white: #ffffff;
            --shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
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
            background: var(--white);
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
                transition: transform 0.35s cubic-bezier(.2, .9, .3, 1);
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
                transition: left 0.32s cubic-bezier(.25, .8, .25, 1);
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
        
        .container {
            max-width: 1000px;
            margin: 30px auto;
            background: var(--white);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        
        h1 {
            text-align: center;
        }
        
        h2 {
            color: var(--blue);
            margin-top: 25px;
        }
        
        p {
            margin-bottom: 15px;
            color: var(--dark-gray);
        }
        
        ul {
            margin-left: 20px;
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
        /* Hamburger button (hidden by default) */
        
        .menu-toggle {
            display: none;
            font-size: 28px;
            cursor: pointer;
            color: #333;
        }
        /* For smaller screens */
        
        @media (max-width: 768px) {
            nav {
                display: none;
                width: 100%;
            }
            nav ul {
                flex-direction: column;
                /* background:  #004aad; */
                padding: 10px;
                border-radius: 8px;
            }
            nav ul li {
                margin: 8px 0;
            }
            .menu-toggle {
                display: block;
            }
            nav.active {
                display: block;
            }
        }
        /* Tablet (768px and below) */
        
        @media (max-width: 992px) {
            header {
                flex-direction: column;
                align-items: flex-start;
            }
            nav ul {
                flex-wrap: wrap;
                gap: 12px;
            }
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
                <li><a href="our_service.php"><b>Our Services</b></a></li>
                <li><a href="Project.php"><b>Our Projects</b></a></li>
                <li><a href="about.php"><b>About Us</b></a></li>
                <li><a href="contact.php"><b>Contact Us</b></a></li>
            </ul>
        </nav>
    </header>

    <h1>Lazri Privacy Policy</h1>
    <p style="text-align: center;">Last Updated: September 12, 2025</p>

    <div class="container">
        <p>Lazri Companies Limited (“we,” “our,” “us”) values your privacy and is committed to protecting your personal data. This Privacy Policy explains how we collect, use, store, and safeguard your information when you interact with our website, services,
            and digital platforms.</p>
        <div class="container">
            <p>Lazri Companies Limited values your privacy and is committed to protecting your personal data. This Privacy Policy explains how we collect, use, store, and safeguard your information when you interact with our website, services, and digital
                platforms.
            </p>
   <h2>1. Information We Collect</h2>
            <p>We may collect the following types of information:</p>
            <ul>
                <li><strong>Personal Information:</strong> Name, email address, phone number, and other details you provide when contacting us or using our services.</li>
                <li><strong>Technical Information:</strong> IP address, browser type, device information, and cookies to improve website performance and user experience.</li>
                <li><strong>Transactional Information:</strong> Records of services purchased, subscription details, and payment information.</li>
                <li><strong>Communication Data:</strong> Any feedback, inquiries, or correspondence sent through our website, email, or social media platforms.</li>
            </ul>

            <h2>2. How We Use Your Information</h2>
            <ul>
                <li>Provide, maintain, and improve our services.</li>
                <li>Respond to inquiries and customer support requests.</li>
                <li>Send important updates, newsletters, and promotional offers (with your consent).</li>
                <li>Ensure website security, fraud prevention, and compliance with legal requirements.</li>
                <li>Personalize user experience and service recommendations.</li>
            </ul>

            <h2>3. Sharing of Information</h2>
            <p>We do <strong>not sell or rent</strong> your personal information. However, we may share data with:</p>
            <ul>
                <li><strong>Service Providers:</strong> Trusted third-party vendors for hosting, payment processing, and analytics.</li>
                <li><strong>Legal Authorities:</strong> When required by law or to protect our legal rights.</li>
                <li><strong>Business Transfers:</strong> In case of mergers, acquisitions, or restructuring, your information may be part of transferred assets.</li>
            </ul>

            <h2>4. Data Security</h2>
            <p>We implement strict security measures including encryption, secure servers, and regular monitoring to protect your data. However, no system is 100% secure, and users share information at their own risk.</p>

            <h2>5. Data Retention</h2>
            <p>We retain personal information only as long as necessary to fulfill service purposes, comply with legal obligations, and resolve disputes.</p>

            <h2>6. Cookies and Tracking</h2>
            <p>Our website uses cookies and similar technologies to enhance user experience, analyze traffic, and deliver relevant advertisements. Users can disable cookies in browser settings.</p>

            <h2>7. Your Rights</h2>
            <p>You may have the right to:</p>
            <ul>
                <li>Access, update, or delete your personal data.</li>
                <li>Object to or restrict certain data processing.</li>
                <li>Withdraw consent to marketing communications.</li>
                <li>Request data portability.</li>
            </ul>
            <p>To exercise these rights, contact us at <strong>info@lazri.co.tz</strong>.</p>

            <h2>8. Third-Party Links</h2>
            <p>Our website may contain links to external sites. We are not responsible for the privacy practices of third-party websites.</p>

            <h2>9. Children's Privacy</h2>
            <p>Our services are not directed to children under 13. We do not knowingly collect data from children.</p>

            <h2>10. Changes to this Policy</h2>
            <p>We may update this Privacy Policy from time to time. Changes will be posted on this page with the updated date.</p>

            <h2>11. Contact Us</h2>
            <p>If you have any questions or concerns regarding this Privacy Policy, contact us at:</p>
            <p><strong>Lazri Companies Limited</strong><br> Email: <a href="mailto:info@lazri.co.tz">info@lazri.co.tz</a><br> Website: <a href="https://www.lazri.co.tz">www.lazri.co.tz</a><br> HQ Office:Samora Avenue,Dar es salaam Tanzania</p>
        </div>

        <!-- Footer -->
        <footer>
            <p>&copy; 2025 Lazri Company Limited. All rights reserved.</p>
        </footer>
        <a href="index.html" class="back-home">⬅ Back to Home</a>

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
        </script>
</body>

</html>