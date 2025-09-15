<?php
session_start();

/* ====== DB CONFIG ====== */
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'lazri';

$conn = new mysqli($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME);
if($conn->connect_error) die('DB connection failed: '.$conn->connect_error);

if(!isset($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
$csrf = $_SESSION['csrf'];

function e($s){ return htmlspecialchars($s,ENT_QUOTES); }

// ====== LOGIN ======
if(isset($_POST['action']) && $_POST['action']==='login'){
    $u = $_POST['username'] ?? '';
    $p = $_POST['password'] ?? '';
    $res = $conn->query("SELECT * FROM admin WHERE username='".$conn->real_escape_string($u)."' AND password='".md5($p)."'");
    if($res && $res->num_rows>0){
        $_SESSION['admin'] = true;
        $_SESSION['admin_user'] = $u;
        header('Location: admin_dashboard.php'); exit;
    } else $error='Incorrect login credentials.';
}

// ====== LOGOUT ======
if(isset($_GET['action']) && $_GET['action']==='logout'){
    session_destroy();
    header('Location: admin_dashboard.php'); exit;
}

// ====== PROTECT PAGE ======
if(!isset($_SESSION['admin']) || $_SESSION['admin']!==true){
    ?>
    <!doctype html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body{font-family:Arial,Segoe UI;background:#f4f7fb;display:flex;align-items:center;justify-content:center;height:100vh;}
        .card{background:#fff;padding:28px;border-radius:12px;box-shadow:0 6px 24px rgba(10,20,40,0.08);width:360px;}
        input{width:100%;padding:10px;margin:8px 0;border-radius:8px;border:1px solid #ddd;}
        button{background:#0b66ff;color:#fff;padding:10px;border-radius:8px;border:none;width:100%;}
        .err{color:#c53030;margin-bottom:10px;}
    </style>
    </head>
    <body>
    <div class="card">
        <h2>Admin Login</h2>
        <?php if(!empty($error)) echo '<div class="err">'.e($error).'</div>'; ?>
        <form method="post">
            <input type="hidden" name="action" value="login">
            <label>Username</label>
            <input type="text" name="username" required>
            <label>Password</label>
            <input type="password" name="password" required>
            <button type="submit">Login</button>
        </form>
    </div>
    </body>
    </html>
    <?php exit;
}

// ====== HANDLE POST ACTIONS ======
$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    if(!isset($_POST['csrf']) || $_POST['csrf']!==$_SESSION['csrf']) die('CSRF token invalid');

    // Add/Edit Project
    if(isset($_POST['form']) && $_POST['form']==='project'){
        $title = $conn->real_escape_string($_POST['title']);
        $desc = $conn->real_escape_string($_POST['description']);
        $category = $conn->real_escape_string($_POST['category']);
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

        $imageName='';
        if(!empty($_FILES['image']['name'])){
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $allowed=['jpg','jpeg','png','webp','gif'];
            if(!in_array(strtolower($ext),$allowed)) $msg='Image type not allowed.';
            else{
                if(!is_dir('uploads')) mkdir('uploads',0755,true);
                $imageName = time().'_'.preg_replace('/[^a-z0-9_\.-]/i','',$_FILES['image']['name']);
                move_uploaded_file($_FILES['image']['tmp_name'],__DIR__.'/uploads/'.$imageName);
            }
        }

        if($id>0){
            if($imageName){
                $stmt = $conn->prepare("UPDATE projects SET title=?, description=?, category=?, image=? WHERE id=?");
                $stmt->bind_param('ssssi',$title,$desc,$category,$imageName,$id);
            } else {
                $stmt = $conn->prepare("UPDATE projects SET title=?, description=?, category=? WHERE id=?");
                $stmt->bind_param('sssi',$title,$desc,$category,$id);
            }
            $msg = $stmt->execute() ? 'Project updated.' : 'Error: '.$stmt->error;
            $stmt->close();
        } else {
            $stmt = $conn->prepare("INSERT INTO projects (title,description,category,image,created_at) VALUES (?,?,?,?,NOW())");
            $stmt->bind_param('ssss',$title,$desc,$category,$imageName);
            $msg = $stmt->execute() ? 'Project added.' : 'Error: '.$stmt->error;
            $stmt->close();
        }
    }

    // Delete Project
    if(isset($_POST['delete_project'])){
        $pid=intval($_POST['delete_project']);
        $res=$conn->query("SELECT image FROM projects WHERE id={$pid}");
        if($res && $row=$res->fetch_assoc()){
            if(!empty($row['image']) && file_exists(__DIR__.'/uploads/'.$row['image'])) @unlink(__DIR__.'/uploads/'.$row['image']);
        }
        $stmt=$conn->prepare("DELETE FROM projects WHERE id=?");
        $stmt->bind_param('i',$pid);
        $msg = $stmt->execute() ? 'Project deleted.' : 'Error: '.$stmt->error;
        $stmt->close();
    }

    // Update Order Status
    if(isset($_POST['update_status'])){
        $oid = intval($_POST['update_status']);
        $status = $_POST['status'];
        $allowed=['pending','active','complete','processed'];
        if(in_array($status,$allowed)){
            $stmt = $conn->prepare("UPDATE orders SET status=? WHERE id=?");
            $stmt->bind_param('si',$status,$oid);
            $msg = $stmt->execute() ? 'Order status updated.' : 'Error: '.$stmt->error;
            $stmt->close();
        } else $msg='Invalid status selected.';
    }

    // Delete Order
    if(isset($_POST['delete_order'])){
        $oid=intval($_POST['delete_order']);
        $stmt=$conn->prepare("DELETE FROM orders WHERE id=?");
        $stmt->bind_param('i',$oid);
        $msg = $stmt->execute() ? 'Order deleted.' : 'Error: '.$stmt->error;
        $stmt->close();
    }

    // Change Admin Password
    if(isset($_POST['change_password'])){
        $current = $_POST['current_pass'] ?? '';
        $newp = $_POST['new_pass'] ?? '';
        $confirm = $_POST['confirm_pass'] ?? '';
        $user = $_SESSION['admin_user'];

        if($newp!==$confirm) $msg='New passwords do not match.';
        else{
            $res=$conn->query("SELECT * FROM admin WHERE username='".$conn->real_escape_string($user)."' AND password='".md5($current)."'");
            if($res && $res->num_rows>0){
                $stmt=$conn->prepare("UPDATE admin SET password=? WHERE username=?");
                $h=md5($newp);
                $stmt->bind_param('ss',$h,$user);
                $msg = $stmt->execute() ? 'Password changed successfully.' : 'Error: '.$stmt->error;
                $stmt->close();
            } else $msg='Current password incorrect.';
        }
    }
}

// Fetch data
$projects_res = $conn->query("SELECT * FROM projects ORDER BY created_at DESC");
$orders_res = $conn->query("SELECT * FROM orders ORDER BY id DESC");
$projects_count = $projects_res ? $projects_res->num_rows : 0;
$orders_count = $orders_res ? $orders_res->num_rows : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lazri Admin Dashboard</title>
<style>
:root{--blue:#0b66ff;--dark-blue:#053a9b;--white:#fff;--muted:#3f3f3f;}
*{margin:0;padding:0;box-sizing:border-box;font-family:Segoe UI,Arial;}
body{display:flex;min-height:100vh;background:#f3f6fb;}
.sidebar{width:240px;background:var(--dark-blue);color:var(--white);display:flex;flex-direction:column;padding-top:20px;position:fixed;height:100vh;}
.sidebar .logo{text-align:center;margin-bottom:30px;}
.sidebar .logo img{width:120px;transition: transform 0.3s;cursor:pointer;}
.sidebar .logo img:hover{transform:scale(1.1);}
.sidebar nav a{display:block;color:var(--white);text-decoration:none;padding:12px 20px;font-weight:500;transition:0.3s;}
.sidebar nav a:hover,.sidebar nav a.active{background:var(--blue);}
.sidebar hr{border-color:rgba(255,255,255,0.2);margin:10px 0;}
.main{margin-left:240px;flex:1;padding:20px;}
.card{background:#fff;padding:16px;border-radius:12px;box-shadow:0 8px 30px rgba(12,24,40,0.06);margin-bottom:20px;}
table{width:100%;border-collapse:collapse;}
th,td{padding:10px;border-bottom:1px solid #eee;text-align:left;}
img.thumb{width:100px;height:60px;object-fit:cover;border-radius:8px;}
.btn{background:var(--blue);color:#fff;padding:8px 12px;border-radius:8px;border:none;cursor:pointer;}
.danger{background:#e11d48;}
form.inline{display:inline;}
.small{font-size:13px;color:var(--muted);}
</style>
</head>
<body>

<div class="sidebar">
  <div class="logo"><img src="./images/Logo2.png" alt="Lazri Logo"></div>
  <nav>
    <a href="#stats" class="active" onclick="showTab('stats')">Quick Stats</a>
    <a href="#projectForm" onclick="showTab('projectForm')">Add/Edit Project</a>
    <a href="#projects" onclick="showTab('projects')">Projects</a>
    <a href="#orders" onclick="showTab('orders')">Orders</a>
    <a href="#settings" onclick="showTab('settings')">Settings</a>
    <hr>
    <a href="index.php" target="_blank">View Site</a>
    <a href="?action=logout">Logout</a>
  </nav>
</div>

<div class="main">
<?php if($msg) echo '<div class="card"><p>'.$msg.'</p></div>'; ?>

<div id="tab-stats" class="tab card">
<h3>Quick Stats</h3>
<p class="small">Projects: <strong><?php echo $projects_count;?></strong></p>
<p class="small">Orders: <strong><?php echo $orders_count;?></strong></p>
</div>

<div id="tab-projectForm" class="tab card" style="display:none">
<h3>Add/Edit Project</h3>
<form method="post" enctype="multipart/form-data">
<input type="hidden" name="csrf" value="<?php echo e($csrf); ?>">
<input type="hidden" name="form" value="project">
<input type="hidden" name="id" id="proj_id" value="">
<label>Title</label>
<input type="text" name="title" id="proj_title" required>
<label>Description</label>
<textarea name="description" id="proj_desc" rows="4" required></textarea>
<label>Category</label>
<select name="category" id="proj_cat">
<option value="completed">completed</option>
<option value="ongoing">ongoing</option>
<option value="upcoming">upcoming</option>
</select>
<label>Image (JPG, PNG)</label>
<input type="file" name="image" accept="image/*">
<div style="display:flex;gap:8px;margin-top:8px">
<button class="btn" type="submit">Save Project</button>
<button type="button" onclick="clearForm()" style="border:1px solid #ddd;background:#fff">Clear</button>
</div>
</form>
</div>

<div id="tab-projects" class="tab card" style="display:none">
<h3>Projects</h3>
<?php if($projects_res && $projects_res->num_rows>0){?>
<table>
<thead><tr><th>Image</th><th>Title</th><th>Category</th><th>Created</th><th>Actions</th></tr></thead>
<tbody>
<?php while($p=$projects_res->fetch_assoc()){?>
<tr>
<td><?php if(!empty($p['image']) && file_exists(__DIR__.'/uploads/'.$p['image'])){?><img class="thumb" src="uploads/<?php echo e($p['image']); ?>"><?php } else { echo '-'; }?></td>
<td><?php echo e($p['title']);?></td>
<td><?php echo e($p['category']);?></td>
<td><?php echo e($p['created_at']);?></td>
<td>
<button class="btn" onclick='populate(<?php echo json_encode($p);?>)'>Edit</button>
<form method="post" class="inline" onsubmit="return confirm('Delete?')">
<input type="hidden" name="csrf" value="<?php echo e($csrf);?>">
<button class="btn danger" name="delete_project" value="<?php echo $p['id'];?>">Delete</button>
</form>
</td>
</tr>
<?php } ?>
</tbody>
</table>
<?php } else echo '<p class="small">No projects yet.</p>';?>
</div>

<div id="tab-orders" class="tab card" style="display:none">
<h3>Orders</h3>
<?php if($orders_res && $orders_res->num_rows>0){?>
<table>
<thead><tr><th>#</th><th>Fullname</th><th>Email</th><th>Phone</th><th>Service</th><th>Status</th><th>Actions</th></tr></thead>
<tbody>
<?php while($o=$orders_res->fetch_assoc()){?>
<tr>
<td><?php echo e($o['id']);?></td>
<td><?php echo e($o['fullname']);?></td>
<td><?php echo e($o['email']);?></td>
<td><?php echo e($o['phone']);?></td>
<td><?php echo e($o['service']);?><?php if(!empty($o['otherservice'])) echo ' / '.e($o['otherservice']);?></td>
<td>
<form method="post" class="inline">
<input type="hidden" name="csrf" value="<?php echo e($csrf);?>">
<select name="status">
<option value="pending" <?php if($o['status']=='pending') echo 'selected';?>>Pending</option>
<option value="active" <?php if($o['status']=='active') echo 'selected';?>>Active</option>
<option value="complete" <?php if($o['status']=='complete') echo 'selected';?>>Complete</option>
<option value="processed" <?php if($o['status']=='processed') echo 'selected';?>>Processed</option>
</select>
<button class="btn" name="update_status" value="<?php echo $o['id'];?>">Update</button>
</form>
<form method="post" class="inline" onsubmit="return confirm('Delete?')">
<input type="hidden" name="csrf" value="<?php echo e($csrf);?>">
<button class="btn danger" name="delete_order" value="<?php echo $o['id'];?>">Delete</button>
</form>
</td>
</tr>
<?php } ?>
</tbody>
</table>
<?php } else echo '<p class="small">No orders yet.</p>';?>
</div>

<div id="tab-settings" class="tab card" style="display:none">
<h3>Settings</h3>
<form method="post">
<input type="hidden" name="csrf" value="<?php echo e($csrf);?>">
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
function showTab(tab){ 
  document.querySelectorAll('.tab').forEach(t=>t.style.display='none');
  document.getElementById('tab-'+tab).style.display='block';
  document.querySelectorAll('.sidebar nav a').forEach(a=>a.classList.remove('active'));
  document.querySelector('.sidebar nav a[href="#'+tab+'"]').classList.add('active');
}
showTab('stats'); // default tab

function populate(obj){
  showTab('projectForm');
  document.getElementById('proj_id').value=obj.id||'';
  document.getElementById('proj_title').value=obj.title||'';
  document.getElementById('proj_desc').value=obj.description||'';
  document.getElementById('proj_cat').value=obj.category||'completed';
}

function clearForm(){
  document.getElementById('proj_id').value='';
  document.getElementById('proj_title').value='';
  document.getElementById('proj_desc').value='';
  document.getElementById('proj_cat').value='completed';
}
</script>

</body>
</html>

<?php $conn->close(); ?>
