<?php
// admin_dashboard.php
session_start();

/* ====== DB CONFIG ====== */
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'lazri';

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
    die('DB connection failed: ' . $conn->connect_error);
}

// Basic admin credentials
$ADMIN_USER = 'admin';
$ADMIN_PASS = '123';

// CSRF token
if (!isset($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(16));
}
$csrf = $_SESSION['csrf'];

// Helper
function e($s){ return htmlspecialchars($s, ENT_QUOTES); }

// ====== AUTH ======
if (isset($_POST['action']) && $_POST['action'] === 'login') {
    $u = $_POST['username'] ?? '';
    $p = $_POST['password'] ?? '';
    if ($u === $GLOBALS['ADMIN_USER'] && $p === $GLOBALS['ADMIN_PASS']) {
        $_SESSION['admin'] = true;
        header('Location: admin_dashboard.php'); exit;
    } else {
        $error = 'Incorrect login credentials.';
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
        body{
          font-family:Arial,Segoe UI;
          background:#f4f7fb;
          display:flex;
          align-items:center;
          justify-content:center;
          height:100vh;
        }
        .card{
          background:#fff;
          padding:28px;
          border-radius:12px;
          box-shadow:0 6px 24px rgba(10,20,40,0.08);
          width:360px
        }
        input{
          width:100%;
          padding:10px;
          margin:8px 0;
          border-radius:8px;
          border:1px solid #ddd;
        }
        button{
          background:#0b66ff;
          color:#fff;
          padding:10px;
          border-radius:8px;
          border:none;
          width:100%;
        }
        .err{
          color:#c53030;
          margin-bottom:10px
        }
      </style>
    </head>
    <body>
      <div class="card">
        <h2>Lazri ompany Limited - Admin Login</h2>
        <?php if(!empty($error)): ?><div class="err"><?php echo e($error); ?></div><?php endif; ?>
        <form method="post">
          <input type="hidden" name="action" value="login">
          <label>Username</label>
          <input type="text" name="username" required>
          <label>Password</label>
          <input type="password" name="password" required>
          <button type="submit">Login</button>
        </form>
        <p style="font-size:12px;color:#666;margin-top:10px">Change admin credentials inside <code>admin_dashboard.php</code> before using production.</p>
      </div>
    </body>
    </html>
    <?php
    exit;
}

// ====== ACTION HANDLERS ======
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
        die('CSRF token invalid');
    }

    // Project add/edit
    if (isset($_POST['form']) && $_POST['form'] === 'project') {
        $title = $conn->real_escape_string($_POST['title']);
        $desc  = $conn->real_escape_string($_POST['description']);
        $category = $conn->real_escape_string($_POST['category']);
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

        if ($id > 0) {
            if ($imageName) {
                $sql = "UPDATE projects SET title=?, description=?, category=?, image=? WHERE id=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ssssi', $title, $desc, $category, $imageName, $id);
            } else {
                $sql = "UPDATE projects SET title=?, description=?, category=? WHERE id=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('sssi', $title, $desc, $category, $id);
            }
            if ($stmt->execute()) $msg = 'Project updated successfully.'; else $msg = 'Error: '.$stmt->error;
            $stmt->close();
        } else {
            $sql = "INSERT INTO projects (title, description, category, image, created_at) VALUES (?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssss', $title, $desc, $category, $imageName);
            if ($stmt->execute()) $msg = 'Project added successfully.'; else $msg = 'Error: '.$stmt->error;
            $stmt->close();
        }
    }

    // Delete project
    if (isset($_POST['delete_project'])) {
        $pid = intval($_POST['delete_project']);
        $res = $conn->query("SELECT image FROM projects WHERE id={$pid}");
        if ($res && $row = $res->fetch_assoc()) {
            if (!empty($row['image']) && file_exists(__DIR__.'/uploads/'.$row['image'])) @unlink(__DIR__.'/uploads/'.$row['image']);
        }
        $stmt = $conn->prepare("DELETE FROM projects WHERE id=?");
        $stmt->bind_param('i',$pid);
        if ($stmt->execute()) $msg = 'Project deleted successfully.'; else $msg = 'Error: '.$stmt->error;
        $stmt->close();
    }

    // Order status update
    if (isset($_POST['update_status'])) {
        $oid = intval($_POST['update_status']);
        $status = $conn->real_escape_string($_POST['status']);
        $stmt = $conn->prepare("UPDATE orders SET status=? WHERE id=?");
        $stmt->bind_param('si',$status,$oid);
        if ($stmt->execute()) $msg = 'Order status updated.'; else $msg = 'Error: '.$stmt->error;
        $stmt->close();
    }

    // Delete order
    if (isset($_POST['delete_order'])) {
        $oid = intval($_POST['delete_order']);
        $stmt = $conn->prepare("DELETE FROM orders WHERE id=?");
        $stmt->bind_param('i',$oid);
        if ($stmt->execute()) $msg = 'Order deleted successfully.'; else $msg = 'Error: '.$stmt->error;
        $stmt->close();
    }

    // Change password
    if (isset($_POST['change_password'])) {
        $current = $_POST['current_pass'] ?? '';
        $new = $_POST['new_pass'] ?? '';
        $confirm = $_POST['confirm_pass'] ?? '';
        if($current === $ADMIN_PASS){
            if($new === $confirm){
                $ADMIN_PASS = $new;
                $msg = 'Password changed successfully. Please refresh.';
            } else $msg='New passwords do not match.';
        } else $msg='Current password incorrect.';
    }
}

// Fetch dashboard data
$projects_res = $conn->query("SELECT * FROM projects ORDER BY created_at DESC");
$orders_res = $conn->query("SELECT * FROM orders ORDER BY id DESC");
$projects_count = $projects_res ? $projects_res->num_rows : 0;
$orders_count = $orders_res ? $orders_res->num_rows : 0;

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
</style>
</head>
<body>
<div class="sidebar">
    <div class="head">Admin Dashboard</div>
  <div class="logo"><img src="./images/Logo2.png" alt="Lazri Logo"></div>
  <nav>
    <a href="#stats" class="active" onclick="showTab('stats')">Quick Stats</a>
    <a href="#projectForm" onclick="showTab('projectForm')">Add/Edit Project</a>
    <a href="#projects" onclick="showTab('projects')">Projects</a>
    <a href="#orders" onclick="showTab('orders')">Orders</a>
    <a href="#settings" onclick="showTab('settings')">Settings</a>
    <hr><br><br><br><br><br><br><br><br><br>
    <a href="index.php" target="_blank">View Site</a>
    <a href="?action=logout">Logout</a>
  </nav>
</div>

<div class="container">
<?php if($msg): ?><div id="popup-msg" class="msg"><?php echo e($msg); ?></div><?php endif; ?>

<div id="tab-stats" class="tab card">
<h3>Quick Stats</h3>
<p class="small">Projects: <strong><?php echo $projects_count; ?></strong></p>
<p class="small">Orders: <strong><?php echo $orders_count; ?></strong></p>
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
<?php if($projects_res && $projects_res->num_rows>0){ ?>
<table>
<thead><tr><th>Image</th><th>Title</th><th>Category</th><th>Created</th><th>Actions</th></tr></thead>
<tbody>
<?php while($p=$projects_res->fetch_assoc()){ ?>
<tr>
<td><?php if(!empty($p['image']) && file_exists(__DIR__.'/uploads/'.$p['image'])){ ?><img class="thumb" src="uploads/<?php echo e($p['image']); ?>"><?php } else echo '-'; ?></td>
<td><?php echo e($p['title']); ?></td>
<td><?php echo e($p['category']); ?></td>
<td><?php echo e($p['created_at']); ?></td>
<td>
<button class="btn" onclick='populate(<?php echo json_encode($p); ?>)'>Edit</button>
<form method="post" class="inline" onsubmit="return confirm('Delete?')">
<input type="hidden" name="csrf" value="<?php echo e($csrf); ?>">
<button class="btn danger" name="delete_project" value="<?php echo $p['id']; ?>">Delete</button>
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
<?php if($orders_res && $orders_res->num_rows>0){ ?>
<table>
<thead><tr><th>#</th><th>Fullname</th><th>Email</th><th>Phone</th><th>Service</th><th>Status</th><th>Actions</th></tr></thead>
<tbody>
<?php while($o=$orders_res->fetch_assoc()){ ?>
<tr>
<td><?php echo e($o['id']); ?></td>
<td><?php echo e($o['fullname']); ?></td>
<td><?php echo e($o['email']); ?></td>
<td><?php echo e($o['phone']); ?></td>
<td><?php echo e($o['service']); ?><?php if(!empty($o['otherservice'])) echo ' / '.e($o['otherservice']); ?></td>
<td><?php echo e($o['status']??'new'); ?></td>
<td>
<form method="post" class="inline">
<input type="hidden" name="csrf" value="<?php echo e($csrf); ?>">
<select name="status" onchange="this.form.submit()">
<option value="pending" <?php if(($o['status']??'')==='pending') echo 'selected'; ?>>Pending</option>
<option value="active" <?php if(($o['status']??'')==='active') echo 'selected'; ?>>Active</option>
<option value="complete" <?php if(($o['status']??'')==='complete') echo 'selected'; ?>>Complete</option>
</select>
<input type="hidden" name="update_status" value="<?php echo $o['id']; ?>">
</form>
<form method="post" class="inline" onsubmit="return confirm('Delete?')">
<input type="hidden" name="csrf" value="<?php echo e($csrf); ?>">
<button class="btn danger" name="delete_order" value="<?php echo $o['id']; ?>">Delete</button>
</form>
</td>
</tr>
<?php } ?>
</tbody>
</table>
<?php } else echo '<p class="small">No orders yet.</p>'; ?>
</div>

<!-- Settings -->
<div id="tab-settings" class="tab card" style="display:none">
<h3>Settings</h3>
<p class="small">Change admin password.</p>
<form method="post">
<input type="hidden" name="csrf" value="<?php echo e($csrf); ?>">
<input type="hidden" name="change_password" value="1">
<label>Current Password</label>
<input type="password" name="current_pass" required>
<label>New Password</label>
<input type="password" name="new_pass" required>
<label>Confirm New Password</label>
<input type="password" name="confirm_pass" required>
<button class="btn" type="submit">Change Password</button>
</form>
</div>

</div>

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

// default show Quick Stats
showTab('stats');
</script>

</body>
</html>
<?php $conn->close(); ?>
