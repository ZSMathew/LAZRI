<?php
// ====== DB Connection ======
$host = "localhost";
$user = "root";
$pass = "";
$db   = "lazri"; 

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Kusoma projects zote
$sql = "SELECT * FROM projects ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html> 
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Our completed, ongoing and upcoming projects at Lazri Company Limited.">
  <title>Lazri Company - Our Projects</title>
  <style>
:root {
  --blue: #0b66ff;
  --dark-blue: #053a9b;
  --gray: #f3f4f6;
  --muted: black;
  --white: #ffffff;
  --shadow: 0 4px 10px rgba(0,0,0,0.1);
  --radius: 12px;
  --sidebar-width: 40%;
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
  display: flex;
  flex-direction: column;
  min-height: 100vh; 
  overflow-x: hidden;
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
  flex-wrap: wrap;
}
header img { 
  height: 50px; 
}
nav ul {
   display: flex;
    gap: 20px; 
    list-style: none; 
    flex-wrap: wrap;
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
   color: #ffd700 
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


.h1{
  text-align: center;
  margin-top: 1rem;
}

/* Filter Buttons */
.filter-buttons {
  text-align: center;
  margin: 2rem 0;
}

.filter-buttons button {
  background: #fff;
  border: 2px solid var(--dark-blue);
  color: var(--dark-blue);
  padding: 0.5rem 1rem;
  margin: 0.3rem;
  border-radius: 20px;
  font-size: 0.9rem;
  cursor: pointer;
  transition: all 0.3s ease;
  display: inline-block;
  min-width: 100px;
}

.filter-buttons button.active,
.filter-buttons button:hover {
  background: var(--dark-blue);
  color: #fff;
}

main {
  flex: 1; 
  display: flex;
  flex-direction: column;
}

section {
  padding: 2rem 1rem;
  max-width: 1200px;
  margin: auto;
  width: 100%;
}

.projects-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 1.5rem;
}

.project-card {
  background: var(--white);
  border-radius: var(--radius);
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  overflow: hidden;
  transform: translateY(40px);
  opacity: 0;
  transition: transform 0.6s ease, box-shadow 0.3s ease, opacity 0.6s ease;
}

.project-card.visible {
  opacity: 1;
  transform: translateY(0);
}

.project-card:hover {
  transform: scale(1.05);
  box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

.project-image {
  width: 100%;
  height: 180px;
  object-fit: cover;
  transition: transform 0.4s ease;
}

.project-card:hover .project-image {
  transform: scale(1.1);
}

.project-content {
  padding: 1.2rem;
}

.project-content h3 {
  font-size: 1.3rem;
  margin-bottom: .5rem;
  color: var(--dark-blue);
}

.project-content p {
  font-size: 0.95rem;
  color: #555;
}

footer {
  background: var(--muted);
  color: #fff;
  text-align: center;
  padding: 1.5rem;
}

footer p {
  font-size: 0.9rem;
  opacity: 0.85;
}

/* Hidden class for filter */
.project-card.hidden {
  opacity: 0;
  transform: scale(0.9);
  pointer-events: none;
  transition: all 0.4s ease;
  display: none; 
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
.mobile-header {
    transition: transform 0.35s cubic-bezier(.25,.8,.25,1);
}
header.sidebar-open .mobile-header {
    transform: translateX(var(--sidebar-width));
}
}
</style>
</head>
<body>
  <!-- Header -->
  <header>
    <h1><img src="./images/Logo2.png" alt="Lazri Company Logo"></h1>
    <nav>
      <ul>
        <li><a href="index.php"><b>Home</b></a></li>
        <li><a href="our_service.php"><b>Our Services</b></a></li>
        <li><a href="Project.php" class="active"><b>Our Projects</b></a></li>
        <li><a href="about.php"><b>About Us</b></a></li>
        <li><a href="contact.php"><b>Contact Us</b></a></li>
      </ul>
    </nav>
  </header>

  <!-- Main content -->
  <main>
    <div class="h1">
      <h1>Our Projects</h1>
      <p>The Work We Have Done, We Are Doing and We Plan to Do</p>
    </div>

    <!-- Filter Buttons -->
    <div class="filter-buttons">
      <button class="active" data-filter="all">All</button>
      <button data-filter="completed">✅ Completed</button>
      <button data-filter="ongoing">🔄 Ongoing</button>
      <button data-filter="upcoming">🚀 Upcoming</button>
    </div>

    <!-- Projects Section -->
    <section>
      <div class="projects-grid">
        <?php if ($result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <div class="project-card <?php echo htmlspecialchars($row['category']); ?>">
              <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" 
                   alt="<?php echo htmlspecialchars($row['title']); ?>" 
                   class="project-image" loading="lazy">
              <div class="project-content">
                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                <p><?php echo htmlspecialchars($row['description']); ?></p>
              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <p style="text-align:center; color:red;">No projects uploaded yet.</p>
        <?php endif; ?>
      </div>
    </section>
  </main>

  <!-- Footer -->
  <footer>
    <p>&copy; 2025 Lazri Company Limited. All Rights Reserved.</p>
  </footer>

  <!-- Scroll Reveal & Filter Script -->
  <script>
    // Scroll Reveal
    const elements = document.querySelectorAll("section h2, .project-card");

    const revealOnScroll = () => {
      let windowHeight = window.innerHeight;
      elements.forEach(el => {
        let position = el.getBoundingClientRect().top;
        if (position < windowHeight - 100) {
          el.classList.add("visible");
        }
      });
    };

    window.addEventListener("scroll", revealOnScroll);
    window.addEventListener("load", revealOnScroll);

    // Filter Function
    const buttons = document.querySelectorAll(".filter-buttons button");
    const cards = document.querySelectorAll(".project-card");

    buttons.forEach(btn => {
      btn.addEventListener("click", () => {
        buttons.forEach(b => b.classList.remove("active"));
        btn.classList.add("active");

        const filter = btn.getAttribute("data-filter");

        cards.forEach(card => {
          if (filter === "all" || card.classList.contains(filter)) {
            card.style.display = "block";
            setTimeout(() => {
              card.classList.remove("hidden");
              card.classList.add("visible");
            }, 50);
          } else {
            card.classList.remove("visible");
            setTimeout(() => {
              card.classList.add("hidden");
              card.style.display = "none";
            }, 400);
          }
        });
      });
    });
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
