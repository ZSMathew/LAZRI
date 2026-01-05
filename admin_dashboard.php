<?php
// admin_dashboard.php
session_start();
require_once 'config/database.php';

if (isset($_SESSION['flash'])) {
    $msg = $_SESSION['flash'];
    unset($_SESSION['flash']);
} else {
    $msg = '';
}
if (!isset($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf'];

// Helper
function e($s){ return htmlspecialchars($s, ENT_QUOTES); }

// ====== AUTH ======
if (isset($_POST['action']) && $_POST['action'] === 'login') {
    $u = $_POST['username'];
    $p = $_POST['password'];

    // Fetch from database
    try {
        $admin = Database::fetchOne("SELECT * FROM admin WHERE username = :username LIMIT 1", ['username' => $u]);

        if ($admin && password_verify($p, $admin['password'])) {
            $_SESSION['admin'] = true;
            header("Location: admin_dashboard.php");
            exit;
        } else {
            $error = "Incorrect login credentials.";
        }
    } catch (Exception $e) {
        $error = "Login error. Please try again.";
        error_log("Login error: " . $e->getMessage());
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: admin_dashboard.php'); exit;
}

// protect pages
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin Login - Lazri</title>
  <style>
    :root {
      --shadow: 0 4px 10px rgba(0,0,0,0.1);  
      --blue: #0b66ff;
      --dark-blue: #053a9b;
    }
    body {
      font-family: Arial, Segoe UI;
      background: #f4f7fb;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }
    img {
      height: 50px;
      display: block;
      margin: 0 auto 10px;
    }
    h2 {
      font-weight: 800;
      text-align: center;
    }
    .card {
      background: #fff;
      padding: 28px;
      border-radius: 12px;
      width: 360px;
      box-shadow: var(--shadow);
    }
    input {
      width: 100%;
      padding: 10px;
      margin: 8px 0;
      border-radius: 8px;
      border: 1px solid #ddd;
      transition: 0.3s;
    }
    input:hover {
      border-color: var(--blue);
      background: #fff;
      box-shadow: 0 0 6px var(--dark-blue);
    }
    button {
      background: var(--blue);
      color: #fff;
      padding: 10px;
      border-radius: 8px;
      border: none;
      width: 100%;
      cursor: pointer;
      transition: 0.3s;
    }
    button:hover {
      background: var(--dark-blue);
      transform: scale(1.05);
    }
    .err {
      color: #c53030;
      margin-bottom: 10px;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="card">
    <img src="./images/Logo.png" alt="Lazri Logo">
    <h2>Lazri - Admin Login</h2>
    <?php if (!empty($error)): ?>
      <div class="err"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="post">
      <input type="hidden" name="action" value="login">
      <label>Username</label>
      <input type="text" name="username" required>
      <label>Password</label>
      <input type="password" name="password" required autocomplete="off">
      <button type="submit">Login</button>
    </form>
    <p style="font-size:12px;color:#666;margin-top:10px">
      Change admin credentials inside <code>admin_dashboard.php</code> before using production.
    </p>
  </div>
</body>
</html>
    <?php
    exit;
}

// ====== ACTION HANDLERS ======
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
        die('CSRF token invalid');
    }

    // Project add/edit
    if (isset($_POST['form']) && $_POST['form'] === 'project') {
        $title = trim($_POST['title']);
        $desc  = trim($_POST['description']);
        $category = trim($_POST['category']);
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

        $imageName = '';
        if (!empty($_FILES['image']['name'])) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $allowed = ['jpg','jpeg','png','webp','gif'];
            if (!in_array(strtolower($ext), $allowed)) {
                $msg = 'Image type not allowed.';
            } else {
                if (!is_dir('uploads')) mkdir('uploads', 0755, true);
                $imageName = time() . '_' . preg_replace('/[^a-z0-9_\.-]/i','', $_FILES['image']['name']);
                move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/uploads/' . $imageName);
            }
        }

        try {
            if ($id > 0) {
                // Update existing project
                $data = [
                    'title' => $title,
                    'description' => $desc,
                    'category' => $category
                ];
                if ($imageName) {
                    $data['image'] = $imageName;
                }
                Database::update('projects', $data, 'id = :id', ['id' => $id]);
                $_SESSION['flash'] = "Project updated successfully.";
                header("Location: admin_dashboard.php#projects");
                exit;
            } else {
                // Insert new project
                $data = [
                    'title' => $title,
                    'description' => $desc,
                    'category' => $category,
                    'image' => $imageName,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                Database::insert('projects', $data);
                $_SESSION['flash'] = "Project added successfully.";
                header("Location: admin_dashboard.php#projects");
                exit;
            }
        } catch (Exception $e) {
            $msg = 'Error: ' . $e->getMessage();
            error_log("Project save error: " . $e->getMessage());
        }
    }

    // Delete project
    if (isset($_POST['delete_project'])) {
        $pid = intval($_POST['delete_project']);
        try {
            // Get image before deleting
            $project = Database::fetchOne("SELECT image FROM projects WHERE id = :id", ['id' => $pid]);
            if ($project && !empty($project['image']) && file_exists(__DIR__.'/uploads/'.$project['image'])) {
                @unlink(__DIR__.'/uploads/'.$project['image']);
            }
            Database::delete('projects', 'id = :id', ['id' => $pid]);
            $_SESSION['flash'] = "Project deleted successfully.";
            header("Location: admin_dashboard.php#projects");
            exit;
        } catch (Exception $e) {
            $msg = 'Error: ' . $e->getMessage();
            error_log("Project delete error: " . $e->getMessage());
        }
    }

    // Delete order
    if (isset($_POST['delete_order'])) {
        $oid = intval($_POST['delete_order']);
        try {
            Database::delete('orders', 'id = :id', ['id' => $oid]);
            $_SESSION['flash'] = "Order deleted successfully.";
            header("Location: admin_dashboard.php#orders");
            exit;
        } catch (Exception $e) {
            $msg = 'Error: ' . $e->getMessage();
            error_log("Order delete error: " . $e->getMessage());
        }
    }

// CHANGE PASSWORD
if (isset($_POST['change_password'])) {

    $current = $_POST['current_pass'] ?? '';
    $new     = $_POST['new_pass'] ?? '';
    $confirm = $_POST['confirm_pass'] ?? '';

    try {
        // Get admin details
        $admin = Database::fetchOne("SELECT * FROM admin WHERE username = :username LIMIT 1", ['username' => 'admin']);

        if ($admin) {
            // Verify current password
            if (!password_verify($current, $admin['password'])) {
                $_SESSION['flash'] = "Current password incorrect.";
                header("Location: admin_dashboard.php#settings");
                exit;
            }

            // Check if new passwords match
            if ($new !== $confirm) {
                $_SESSION['flash'] = "New passwords do not match.";
                header("Location: admin_dashboard.php#settings");
                exit;
            }

            // Update password
            $new_hash = password_hash($new, PASSWORD_DEFAULT);
            Database::update('admin', ['password' => $new_hash], 'id = :id', ['id' => $admin['id']]);
            $_SESSION['flash'] = "Password changed successfully.";
            header("Location: admin_dashboard.php#settings");
            exit;
        } else {
            $_SESSION['flash'] = "Admin account not found.";
            header("Location: admin_dashboard.php#settings");
            exit;
        }
    } catch (Exception $e) {
        $_SESSION['flash'] = "Error updating password: " . $e->getMessage();
        error_log("Password change error: " . $e->getMessage());
        header("Location: admin_dashboard.php#settings");
        exit;
    }
}

// Handle comment replies
if(isset($_POST['send_reply'])){
    $id = intval($_POST['comment_id']);
    $reply = trim($_POST['reply']);
    $email = $_POST['email'];

    try {
        // Save reply in DB
        Database::update('comments', ['reply' => $reply], 'id = :id', ['id' => $id]);

        // Send email
        $subject = "Reply from Lazri Company";
        $headers = "From: noreply@lazri.com\r\n";
        mail($email, $subject, $reply, $headers);

        $_SESSION['flash'] = "Reply sent successfully!";
        header("Location: admin_dashboard.php#comments");
        exit;
    } catch (Exception $e) {
        $msg = "Error sending reply: " . $e->getMessage();
        error_log("Comment reply error: " . $e->getMessage());
    }
}

}
// Fetch dashboard data
try {
    $projects = Database::fetchAll("SELECT * FROM projects ORDER BY created_at DESC");
    $orders = Database::fetchAll("SELECT * FROM orders ORDER BY id DESC");
    $comments = Database::fetchAll("SELECT * FROM comments ORDER BY id DESC");
    
    $projects_count = count($projects);
    $orders_count = count($orders);
    $comments_count = count($comments);
} catch (Exception $e) {
    error_log("Dashboard data fetch error: " . $e->getMessage());
    $projects = [];
    $orders = [];
    $comments = [];
    $projects_count = 0;
    $orders_count = 0;
    $comments_count = 0;
}
?>
<?php
if (isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $id = intval($_POST['id']);
    $status = trim($_POST['status']);
    $type = $_POST['type']; // Project or Order

    try {
        if ($type === 'project') {
            Database::update('projects', ['status' => $status], 'id = :id', ['id' => $id]);
        } else {
            Database::update('orders', ['status' => $status], 'id = :id', ['id' => $id]);
        }
        echo json_encode(['success' => true, 'status' => $status]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Lazri ompany Limited - Admin Dashboard</title>
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
* { 
    box-sizing:border-box;
    margin:0;
    padding:0; 
}
body{
    font-family:Segoe UI,Arial;
    background:#f3f6fb;
    margin:0;
}
header {
  background: var(--dark-blue);
  display:flex; 
  justify-content:space-between; 
  align-items:center;
  padding:12px 24px; 
  box-shadow:var(--shadow); 
  position:sticky; 
  top:0; z-index:1000;
}

.sidebar{
    width:240px;
    background:var(--dark-blue);
    color:var(--white);
    display:flex;
    flex-direction:column;
    padding-top:20px;
    position:fixed;
    height:100vh;
}
.sidebar .logo{
    text-align:center;
    margin-bottom:30px;
}
.sidebar .logo img{
    width:120px;
    transition: transform 0.3s;
    cursor:pointer;
}
.sidebar .logo img:hover{
    transform:scale(1.1);
}
.sidebar nav a{
    display:block;
    color:var(--white);
    text-decoration:none;
    padding:12px 20px;
    font-weight:500;
    transition:0.3s;
}
.sidebar nav a:hover,.sidebar nav a.active{
    background:var(--blue); 
    color: #ffd700;
    font-weight: bold;
}
.sidebar hr{
    border-color:rgba(255,255,255,0.2);
    margin:10px 0;
}

.head{
    color:#fff;
    font-weight:500;
    text-align:center;
    font-size:20px;
}
.container{
    margin-left:260px; 
    padding:20px;
}

.stats-grid {
  display: flex;
  gap: 20px;
  flex-wrap: wrap;
  margin-top: 15px;
}

.stat-box {
  flex: 1;
  min-width: 200px;
  padding: 25px;
  border-radius: 16px;
  text-align: center;
  color: #fff;
  box-shadow: 0 6px 15px rgba(0,0,0,0.1);
  transition: transform 0.3s, box-shadow 0.3s;
}
.stat-box:hover {
  transform: translateY(-6px);
  box-shadow: 0 12px 25px rgba(0,0,0,0.2);
}

.stat-box h2 {
  font-size: 48px;
  margin-bottom: 6px;
  font-weight: 800;
}

.stat-box p {
  font-size: 18px;
  margin: 0;
  font-weight: 500;
}

/* colors */
.stat-projects { background: linear-gradient(135deg,#0b66ff,#053a9b); }
.stat-orders   { background: linear-gradient(135deg,#16a34a,#065f46); }
.stat-comments { background: linear-gradient(135deg,#facc15,#ca8a04); }


.tab.card {
    background: linear-gradient(145deg,#ffffff,#f0f4ff);
    padding: 30px;
    border-radius:16px;
    box-shadow:0 12px 40px rgba(0,0,0,0.08);
    transition: transform 0.3s, box-shadow 0.3s;
}
.tab.card:hover { 
    transform: translateY(-4px); 
    box-shadow:0 16px 50px rgba(0,0,0,0.15); 
}

input, select, textarea{
  width:100%;
 padding:12px 15px; 
 border-radius:12px; 
 border:1px solid #d1d5db;
  margin:10px 0; 
  font-size:14px; 
  transition:0.3s;
}
input:focus, select:focus, textarea:focus{ 
    border-color:var(--blue); 
    box-shadow:0 0 8px rgba(11,102,255,0.2);
     outline:none;
     }

button.btn{
  background: linear-gradient(90deg,#0b66ff,#053a9b); 
  color:#fff; 
  padding:10px 18px; 
  border-radius:12px; 
  border:none; 
  cursor:pointer; 
  font-weight:500; 
  transition:0.3s;
}
button.btn:hover{ 
    background: linear-gradient(90deg,#053a9b,#0b66ff); 
    transform:translateY(-2px); 
    box-shadow:0 8px 20px rgba(11,102,255,0.3); 
}
button.danger{ 
    background: linear-gradient(90deg,#e11d48,#9f1239);
}
button.danger:hover{ 
    transform:translateY(-2px); 
    box-shadow:0 8px 20px rgba(225,29,72,0.3); 
}

.tab.card h3{ 
    margin-bottom:20px; 
    font-size:24px; 
    color:var(--blue); 
    border-bottom:2px solid #0b66ff33; 
    padding-bottom:6px; 
}
.small{ 
    color:#555; 
    font-size:13px; 
    margin-bottom:12px;
 }

nav a {
  display: flex;
  align-items: center;
  gap: 8px; /* nafasi kati ya icon na text */
  text-decoration: none;
  padding: 10px;
  color: #333;
}

nav a i {
  min-width: 18px; /* ili icons zi-align vizuri */
} 

table{
    width:100%; 
    border-collapse:collapse;
}
th,td{
    padding:10px;
    border-bottom:1px solid #eee;
    text-align:left;
}
img.thumb{
    width:100px;
    height:60px;
    object-fit:cover;
    border-radius:8px;
}
.actions button{
    margin-right:6px;
}
form.inline{
    display:inline;
}

input:hover {
  border-color: var(--blue);
  background: #fff;
  box-shadow: 0 0 6px var(--dark-blue);
}

.msg{
    padding:10px; 
    background:#e6ffed; 
    border:1px solid #b7f3c7; 
    border-radius:8px; 
    position:fixed; 
    top:50%; left:50%; 
    transform:translate(-50%,-50%); 
    z-index:999; 
    box-shadow:0 4px 12px rgba(0,0,0,0.1); 
    opacity:0; 
    animation:fadein 0.5s forwards, fadeout 0.5s 7.5s forwards;
}
@keyframes fadein{
    from{opacity:0;transform:translate(-50%,-60%);} to{opacity:1;transform:translate(-50%,-50%);}
}
@keyframes fadeout{
    from{opacity:1;} to{opacity:0;}
}

@media(max-width:768px){
    .container{margin-left:0; padding:10px;} .sidebar{position:relative;width:100%;}
    }
.modal-overlay {
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.6);
  display: none;
  justify-content: center;
  align-items: center;
  z-index: 2000;
}
.modal {
  background: #fff;
  padding: 20px;
  border-radius: 12px;
  width: 320px;
  text-align: center;
  box-shadow: 0 6px 20px rgba(0,0,0,0.2);
  animation: fadeIn 0.3s ease;
}
@keyframes fadeIn {
  from {opacity:0; transform:scale(0.9);}
  to {opacity:1; transform:scale(1);}
}
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <?php if (!empty($msg)): ?>
    <div id="popup-msg" class="msg">
        <?php echo htmlspecialchars($msg); ?>
    </div>

    <script>
        // Hii inaficha message baada ya sekunde 3
        setTimeout(() => {
            const popup = document.getElementById('popup-msg');
            if (popup) popup.style.display = 'none';
        }, 3000);
    </script>
<?php endif; ?>

<div class="sidebar">
    <div class="head">Admin Dashboard</div>
  <div class="logo"><img src="./images/Logo2.png" alt="Lazri Logo"></div>
  <nav>
  <a href="#stats" class="active" onclick="showTab('stats')">
    <i class="fa fa-chart-line"></i> Quick Stats
  </a>
  <a href="#projectForm" onclick="showTab('projectForm')">
    <i class="fa fa-folder-plus"></i> Add/Edit Project
  </a>
  <a href="#projects" onclick="showTab('projects')">
    <i class="fa fa-folder"></i> Projects
  </a>
  <a href="#orders" onclick="showTab('orders')">
    <i class="fa fa-shopping-cart"></i> Orders
  </a>
  </a>
  <a href="#comments" onclick="showTab('comments')">
    <i class="fa fa-comments"></i> Comments
  </a>
  <a href="#settings" onclick="showTab('settings')">
    <i class="fa fa-cog"></i> Settings
  </a>
  <hr>
  <br><br><br><br><br><br><br>
  <a href="index.php" target="_blank">
    <i class="fa fa-globe"></i> View Site
  </a>
  <a href="?action=logout">
    <i class="fa fa-sign-out-alt"></i> Logout
  </a>
</nav>
</div>

<div class="container">

<div id="tab-stats" class="tab card">
  <h3>Quick Stats</h3>

  <div class="stats-grid">

    <div class="stat-box stat-projects">
      <i class="fa fa-folder fa-2x" style="margin-bottom:10px;"></i>
      <h2><?php echo $projects_count; ?></h2>
      <p>Projects</p>
    </div>

    <div class="stat-box stat-orders">
      <i class="fa fa-shopping-cart fa-2x" style="margin-bottom:10px;"></i>
      <h2><?php echo $orders_count; ?></h2>
      <p>Orders</p>
    </div>

    <div class="stat-box stat-comments">
      <i class="fa fa-comments fa-2x" style="margin-bottom:10px;"></i>
      <h2><?php echo $comments_count; ?></h2>
      <p>Comments</p>
    </div>

  </div>
</div>


<!-- Add/Edit Project -->
<div id="tab-projectForm" class="tab card" style="display:none">
<h3>Add / Edit Project</h3>
<p class="small">Fill the form to add or edit a project.</p>
<form method="post" enctype="multipart/form-data">
<input type="hidden" name="csrf" value="<?php echo e($csrf); ?>">
<input type="hidden" name="form" value="project">
<input type="hidden" name="id" id="proj_id" value="">
<label>Title</label>
<input type="text" name="title" id="proj_title" required placeholder="Enter project title">
<label>Description</label>
<textarea name="description" id="proj_desc" rows="4" required placeholder="Project description"></textarea>
<label>Category</label>
<select name="category" id="proj_cat">
<option value="completed">Completed</option>
<option value="ongoing">Ongoing</option>
<option value="upcoming">Upcoming</option>
</select>
<label>Image (JPG, PNG)</label>
<input type="file" name="image" accept="image/*">
<div style="display:flex;gap:10px;margin-top:15px">
<button class="btn" type="submit">Save Project</button>
<button type="button" onclick="clearForm()" class="btn" style="background: var(--gray);color:#0b66ff;border:2px solid #0b66ff">Clear</button>
</div>
</form>
</div>

<!-- Projects -->
<div id="tab-projects" class="tab card" style="display:none">
<h3>Projects</h3>
<?php if(!empty($projects)){ ?>
<table>
<thead><tr><th>Image</th><th>Title</th><th>Category</th><th>Created</th><th>Actions</th></tr></thead>
<tbody>
<?php foreach($projects as $p){ ?>
<tr>
<td><?php if(!empty($p['image']) && file_exists(__DIR__.'/uploads/'.$p['image'])){ ?><img class="thumb" src="uploads/<?php echo e($p['image']); ?>"><?php } else echo '-'; ?></td>
<td><?php echo e($p['title']); ?></td>
<td><?php echo e($p['category']); ?></td>
<td><?php echo e($p['created_at']); ?></td>
<td>
<button class="btn" onclick='populate(<?php echo json_encode($p); ?>)'>Edit</button>

<!-- Futa project -->
<form method="post" class="inline delete-form">
  <input type="hidden" name="csrf" value="<?php echo e($csrf); ?>">
  <button type="button" 
          class="btn danger delete-btn" 
          data-type="Project" 
          data-id="<?php echo $p['id']; ?>">Delete</button>
</form>

</td>
</tr>
<?php } ?>
</tbody>
</table>
<?php } else echo '<p class="small">No projects yet.</p>'; ?>
</div>

<!-- Orders -->
<div id="tab-orders" class="tab card" style="display:none">
<h3>Orders</h3>
<?php if(!empty($orders)){ ?>
<table>
<thead><tr><th>#</th><th>Full Name</th><th>Email</th><th>Phone</th><th>Service</th><th>Status</th><th>Actions</th></tr></thead>
<tbody>
<?php foreach($orders as $o){ ?>
<tr>
<td><?php echo e($o['id']); ?></td>
<td><?php echo e($o['fullname']); ?></td>
<td><?php echo e($o['email']); ?></td>
<td><?php echo e($o['phone']); ?></td>
<td><?php echo e($o['service']); ?><?php if(!empty($o['otherservice'])) echo ' / '.e($o['otherservice']); ?></td>
<td><?php echo e($o['status']??'new'); ?></td>
<td>
<form class="inline update-status-form" data-id="<?php echo $o['id']; ?>">
<select onchange="syncStatus(<?php echo $o['id']; ?>, this.value)">
    <option value="pending" <?php if(($o['status']??'')=='pending') echo 'selected'; ?>>Pending</option>
    <option value="processing" <?php if(($o['status']??'')=='processing') echo 'selected'; ?>>Processing</option>
    <option value="completed" <?php if(($o['status']??'')=='completed') echo 'selected'; ?>>Completed</option>
</select>

</form>
<!-- Futa order -->
<form method="post" class="inline delete-form">
  <input type="hidden" name="csrf" value="<?php echo e($csrf); ?>">
  <button type="button" class="btn danger delete-btn" data-type="Order" data-id="<?php echo $o['id']; ?>">Delete</button>
</form>
</td>
</tr>
<?php } ?>
</tbody>
</table>
<?php } else echo '<p class="small">No orders yet.</p>'; ?>
</div>

<!-- Comments -->
<div id="tab-comments" class="tab card" style="display:none">
<h3>Comments</h3>

<?php if($comments_count > 0): ?>
    
    <?php foreach($comments as $c): ?>
    
    <div style="
        background:#fff;
        padding:15px;
        border-radius:12px;
        margin-bottom:15px;
        box-shadow:0 4px 12px rgba(0,0,0,0.08);
    ">

        <p><strong>Name:</strong> <?php echo e($c['name']); ?></p>
        <p><strong>Email:</strong> <?php echo e($c['email']); ?></p>
        <p><strong>Phone:</strong> <?php echo e($c['phone']); ?></p>
        <p><strong>Subject:</strong> <?php echo e($c['subject']); ?></p>
        <p><strong>Message:</strong><br> <?php echo nl2br(e($c['message'])); ?></p>

        <?php if(!empty($c['reply'])): ?>
            <div style="
                margin-top:10px;
                padding:12px;
                background:#e6f7ff;
                border-left:4px solid #0b66ff;
                border-radius:8px;
            ">
                <strong>Admin Reply:</strong><br>
                <?php echo nl2br(e($c['reply'])); ?>
            </div>
        <?php endif; ?>

        <br>
        <button class="btn" onclick="openReplyModal(
            <?php echo $c['id']; ?>,
            '<?php echo e($c['email']); ?>'
        )">Reply</button>

    </div>

    <?php endforeach; ?>

<?php else: ?>
    <p class="small">No comments yet.</p>
<?php endif; ?>

</div>

<!-- Settings Tab -->
<div id="tab-settings" class="tab card" style="display:none">
  <h3>Settings</h3>
  <p class="small">Change admin password below.</p>
  <form method="post">
    <input type="hidden" name="csrf" value="<?php echo e($csrf); ?>">
    <input type="hidden" name="change_password" value="1">

    <label>Current Password</label>
    <input type="password" name="current_pass" required placeholder="Enter current password">

    <label>New Password</label>
    <input type="password" name="new_pass" required placeholder="Enter new password">

    <label>Confirm New Password</label>
    <input type="password" name="confirm_pass" required placeholder="Confirm new password">

    <button class="btn" type="submit">Change Password</button>
  </form>
</div>
<!-- Delete Confirmation Modal -->
<div class="modal-overlay" id="deleteModal">
  <div class="modal">
    <h3 id="deleteText">Do you want to delete this item?</h3>
    <div style="margin-top:15px;">
      <button class="btn danger" id="confirmDeleteBtn">Yes</button>
      <button class="btn" style="background:#ccc;color:#333" id="cancelDeleteBtn">Cancel</button>
    </div>
  </div>
</div>
<!-- reply comment -->
<div id="replyModal" class="modal-overlay">
  <div class="modal">
    <h3>Reply to Comment</h3>

    <form method="post">
      <input type="hidden" id="reply_comment_id" name="comment_id">
      <input type="hidden" id="reply_email" name="email">

      <textarea name="reply" placeholder="Type your reply..." required></textarea>
      <br>
      <button class="btn" type="submit" name="send_reply">Send Reply</button>
    </form>

    <button class="btn danger" onclick="closeReplyModal()">Close</button>
  </div>
</div>
    
<script>
function openReplyModal(id, email){
    document.getElementById("reply_comment_id").value = id;
    document.getElementById("reply_email").value = email;
    document.getElementById("replyModal").style.display = "flex";
}

function closeReplyModal(){
    document.getElementById("replyModal").style.display = "none";
}
</script>

<script>
function showTab(id){
  document.querySelectorAll('.tab.card').forEach(t=>t.style.display='none');
  document.getElementById('tab-'+id).style.display='block';
  document.querySelectorAll('.sidebar nav a').forEach(a=>a.classList.remove('active'));
  document.querySelector('.sidebar nav a[href="#'+id+'"]').classList.add('active');
}
function populate(obj){
  document.getElementById('proj_id').value = obj.id||'';
  document.getElementById('proj_title').value = obj.title||'';
  document.getElementById('proj_desc').value = obj.description||'';
  document.getElementById('proj_cat').value = obj.category||'completed';
  showTab('projectForm');
}
function clearForm(){
  document.getElementById('proj_id').value='';
  document.getElementById('proj_title').value='';
  document.getElementById('proj_desc').value='';
  document.getElementById('proj_cat').value='completed';
}
showTab('stats');

let formToSubmit = null;

document.querySelectorAll(".delete-btn").forEach(btn => {
  btn.addEventListener("click", function () {
    formToSubmit = this.closest("form");
    document.getElementById("deleteText").textContent =
      "Are you sure you want to delete this " + this.dataset.type + "?";
    document.getElementById("deleteModal").style.display = "flex";
  });
});

document.getElementById("confirmDeleteBtn").addEventListener("click", function () {
  if (formToSubmit) {
    // Kwa Orders, ubadilishe button kuwa input hidden
    const btn = formToSubmit.querySelector(".delete-btn");
    if (btn && btn.dataset.id) {
      const hidden = document.createElement('input');
      hidden.type = 'hidden';
      hidden.name = btn.dataset.type === "Order" ? 'delete_order' : 'delete_project';
      hidden.value = btn.dataset.id;
      formToSubmit.appendChild(hidden);
    }
    formToSubmit.submit();
  }
  closeModal();
});


document.getElementById("cancelDeleteBtn").addEventListener("click", function () {
  closeModal();
});

function closeModal() {
  document.getElementById("deleteModal").style.display = "none";
}
document.querySelectorAll(".status-select").forEach(select => {
  select.addEventListener("change", function() {
    const id = this.dataset.id;
    const type = this.dataset.type;
    const newStatus = this.value;

    fetch("admin_dashboard.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `action=update_status&id=${id}&type=${type}&status=${encodeURIComponent(newStatus)}`
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        alert("Status updated to " + data.status);
      } else {
        alert("Error: " + data.error);
      }
    })
    .catch(err => console.error(err));
  });
});


// function to show temporary message
function showMessage(msg){
  const popup = document.createElement('div');
  popup.className = 'msg';
  popup.textContent = msg;
  document.body.appendChild(popup);
  setTimeout(()=>popup.remove(), 3000);
}
// Reply Modal Functions
function openReplyModal(id, email){
    document.getElementById('reply_comment_id').value = id;
    document.getElementById('reply_email').value = email;
    document.getElementById('replyModal').style.display = 'flex';
}

function closeReplyModal(){
    document.getElementById('replyModal').style.display = 'none';
}



function syncStatus(orderId, newStatus) {
    let form = new FormData();
    form.append("action", "update_status");
    form.append("id", orderId);
    form.append("status", newStatus);
    form.append("type", "order");

    fetch("admin_dashboard.php", {
        method: "POST",
        body: form
    }).then(r => r.json()).then(data => {
        if (data.success) {
            alert("Status updated to " + newStatus);
            location.reload();
        } else {
            alert("Error: " + data.error);
        }
    });
}
</script>

</body>
</html>
        