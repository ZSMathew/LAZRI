<?php
// admin_dashboard.php
// Single-file admin dashboard for Lazri Company
// Requirements: PHP 7+, mysqli, sessions, a writable `uploads/` folder
// Place this file in your project root (same level as existing pages). Protect with .htaccess on production.

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

// Basic admin credentials - change to real users or integrate with users table
$ADMIN_USER = 'admin';
$ADMIN_PASS = '123'; // change this in production

// Simple CSRF token
if (!isset($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(16));
}
$csrf = $_SESSION['csrf'];

// Helper: sanitize output
function e($s){ return htmlspecialchars($s, ENT_QUOTES); }

// ====== AUTH ======
if (isset($_POST['action']) && $_POST['action'] === 'login') {
    $u = $_POST['username'] ?? '';
    $p = $_POST['password'] ?? '';
    if ($u === $GLOBALS['ADMIN_USER'] && $p === $GLOBALS['ADMIN_PASS']) {
        $_SESSION['admin'] = true;
        header('Location: admin_dashboard.php'); exit;
    } else {
        $error = 'Taarifa za kuingia si sahihi.';
    }
}
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: admin_dashboard.php'); exit;
}

// protect pages
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    // show login form
    ?>
    <!doctype html>
    <html lang="sw">
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width,initial-scale=1">
      <title>Admin Login - Lazri</title>
      <style>
        body{font-family:Arial,Segoe UI;background:#f4f7fb;display:flex;align-items:center;justify-content:center;height:100vh}
        .card{background:#fff;padding:28px;border-radius:12px;box-shadow:0 6px 24px rgba(10,20,40,0.08);width:360px}
        input{width:100%;padding:10px;margin:8px 0;border-radius:8px;border:1px solid #ddd}
        button{background:#0b66ff;color:#fff;padding:10px;border-radius:8px;border:none;width:100%}
        .err{color:#c53030;margin-bottom:10px}
      </style>
    </head>
    <body>
      <div class="card">
        <h2>Ingia kama Admin</h2>
        <?php if(!empty($error)): ?><div class="err"><?php echo e($error); ?></div><?php endif; ?>
        <form method="post">
          <input type="hidden" name="action" value="login">
          <label>Jina la mtumiaji</label>
          <input type="text" name="username" required>
          <label>Nywila</label>
          <input type="password" name="password" required>
          <button type="submit">Ingia</button>
        </form>
        <p style="font-size:12px;color:#666;margin-top:10px">Badilisha admin credentials ndani ya faili <code>admin_dashboard.php</code> kabla ya kutumia production.</p>
      </div>
    </body>
    </html>
    <?php
    exit;
}

// ====== ACTION HANDLERS (Projects / Orders) ======
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // verify csrf token
    if (!isset($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
        die('CSRF token invalid');
    }

    // ADD or EDIT project
    if (isset($_POST['form']) && $_POST['form'] === 'project') {
        $title = $conn->real_escape_string($_POST['title']);
        $desc  = $conn->real_escape_string($_POST['description']);
        $category = $conn->real_escape_string($_POST['category']);
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

        // handle file upload
        $imageName = '';
        if (!empty($_FILES['image']['name'])) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $allowed = ['jpg','jpeg','png','webp','gif'];
            if (!in_array(strtolower($ext), $allowed)) {
                $msg = 'Aina ya picha haikubaliwa.';
            } else {
                if (!is_dir('uploads')) mkdir('uploads', 0755, true);
                $imageName = time() . '_' . preg_replace('/[^a-z0-9_\.-]/i','', $_FILES['image']['name']);
                move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/uploads/' . $imageName);
            }
        }

        if ($id > 0) {
            // update
            if ($imageName) {
                $sql = "UPDATE projects SET title=?, description=?, category=?, image=? WHERE id=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ssssi', $title, $desc, $category, $imageName, $id);
            } else {
                $sql = "UPDATE projects SET title=?, description=?, category=? WHERE id=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('sssi', $title, $desc, $category, $id);
            }
            if ($stmt->execute()) $msg = 'Project imesasishwa.'; else $msg = 'Hitilafu: ' . $stmt->error;
            $stmt->close();
        } else {
            $sql = "INSERT INTO projects (title, description, category, image, created_at) VALUES (?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssss', $title, $desc, $category, $imageName);
            if ($stmt->execute()) $msg = 'Project imeongezwa.'; else $msg = 'Hitilafu: ' . $stmt->error;
            $stmt->close();
        }
    }

    // DELETE project
    if (isset($_POST['delete_project'])) {
        $pid = intval($_POST['delete_project']);
        // remove image file
        $res = $conn->query("SELECT image FROM projects WHERE id={$pid}");
        if ($res && $row = $res->fetch_assoc()) {
            if (!empty($row['image']) && file_exists(__DIR__ . '/uploads/' . $row['image'])) {
                @unlink(__DIR__ . '/uploads/' . $row['image']);
            }
        }
        $stmt = $conn->prepare("DELETE FROM projects WHERE id=?");
        $stmt->bind_param('i',$pid);
        if ($stmt->execute()) $msg = 'Project imefutwa.'; else $msg = 'Hitilafu: ' . $stmt->error;
        $stmt->close();
    }

    // MARK order as processed or delete
    if (isset($_POST['mark_order'])) {
        $oid = intval($_POST['mark_order']);
        $stmt = $conn->prepare("UPDATE orders SET status='processed' WHERE id=?");
        $stmt->bind_param('i',$oid);
        if ($stmt->execute()) $msg = 'Order imesemwa kama processed.'; else $msg = 'Hitilafu: ' . $stmt->error;
        $stmt->close();
    }
    if (isset($_POST['delete_order'])) {
        $oid = intval($_POST['delete_order']);
        $stmt = $conn->prepare("DELETE FROM orders WHERE id=?");
        $stmt->bind_param('i',$oid);
        if ($stmt->execute()) $msg = 'Order imefutwa.'; else $msg = 'Hitilafu: ' . $stmt->error;
        $stmt->close();
    }
}

// ====== Fetch data for dashboard ======
$projects_res = $conn->query("SELECT * FROM projects ORDER BY created_at DESC");
$orders_res = $conn->query("SELECT * FROM orders ORDER BY id DESC");
$projects_count = $projects_res ? $projects_res->num_rows : 0;
$orders_count = $orders_res ? $orders_res->num_rows : 0;

// ====== PAGE OUTPUT ======
?>
<!doctype html>
<html lang="sw">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin Dashboard - Lazri</title>
  <style>
    :root{--blue:#0b66ff;--dark:#053a9b;--muted:#6b7280}
    body{font-family:Segoe UI,Arial;background:#f3f6fb;margin:0}
    header{background:var(--dark);color:#fff;padding:12px 20px;display:flex;justify-content:space-between;align-items:center}
    .container{max-width:1200px;margin:20px auto;padding:0 16px}
    .grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:16px}
    .card{background:#fff;padding:16px;border-radius:12px;box-shadow:0 8px 30px rgba(12,24,40,0.06)}
    table{width:100%;border-collapse:collapse}
    th,td{padding:10px;border-bottom:1px solid #eee;text-align:left}
    img.thumb{width:100px;height:60px;object-fit:cover;border-radius:8px}
    .actions button{margin-right:6px}
    .btn{background:var(--blue);color:#fff;padding:8px 12px;border-radius:8px;border:none;cursor:pointer}
    .danger{background:#e11d48}
    form.inline{display:inline}
    .msg{padding:10px;background:#e6ffed;border:1px solid #b7f3c7;margin-bottom:12px;border-radius:8px}
    input,select,textarea{width:100%;padding:8px;border-radius:8px;border:1px solid #ddd;margin:6px 0}
    .small{font-size:13px;color:var(--muted)}
  </style>
</head>
<body>
  <header>
    <div><strong>Lazri Admin</strong></div>
    <div>
      <a href="../index.php" style="color:#fff;text-decoration:none;margin-right:12px">View Site</a>
      <a href="?action=logout" style="color:#fff;text-decoration:none">Logout</a>
    </div>
  </header>

  <div class="container">
    <?php if($msg): ?>
      <div class="msg"><?php echo e($msg); ?></div>
    <?php endif; ?>

    <div class="grid">
      <div class="card">
        <h3>Quick Stats</h3>
        <p class="small">Projects: <strong><?php echo $projects_count; ?></strong></p>
        <p class="small">Orders: <strong><?php echo $orders_count; ?></strong></p>
      </div>

      <div class="card">
        <h3>Add / Edit Project</h3>
        <p class="small">To edit an existing project, click "Edit" on a project card below and it will populate the form.</p>
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
    </div>

    <div style="height:20px"></div>

    <div class="grid">
      <div class="card" style="grid-column:span 1;">
        <h3>Projects</h3>
        <?php if($projects_res && $projects_res->num_rows>0): ?>
        <table>
          <thead><tr><th>Image</th><th>Title</th><th>Category</th><th>Created</th><th>Actions</th></tr></thead>
          <tbody>
            <?php while($p = $projects_res->fetch_assoc()): ?>
              <tr>
                <td><?php if(!empty($p['image']) && file_exists(__DIR__.'/uploads/'.$p['image'])): ?><img class="thumb" src="uploads/<?php echo e($p['image']); ?>"><?php else: ?>-<?php endif; ?></td>
                <td><?php echo e($p['title']); ?></td>
                <td><?php echo e($p['category']); ?></td>
                <td><?php echo e($p['created_at']); ?></td>
                <td class="actions">
                  <button class="btn" onclick='populate(<?php echo json_encode($p); ?>)'>Edit</button>
                  <form method="post" class="inline" onsubmit="return confirm('Unataka kufuta project hii?')">
                    <input type="hidden" name="csrf" value="<?php echo e($csrf); ?>">
                    <button class="btn danger" name="delete_project" value="<?php echo $p['id']; ?>">Delete</button>
                  </form>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
        <?php else: ?>
          <p class="small">Hakuna project iliyo uploaded.</p>
        <?php endif; ?>
      </div>

      <div class="card" style="grid-column:span 1;">
        <h3>Orders</h3>
        <?php if($orders_res && $orders_res->num_rows>0): ?>
        <table>
          <thead><tr><th>#</th><th>Fullname</th><th>Email</th><th>Phone</th><th>Service</th><th>Status</th><th>Actions</th></tr></thead>
          <tbody>
            <?php while($o = $orders_res->fetch_assoc()): ?>
              <tr>
                <td><?php echo e($o['id']); ?></td>
                <td><?php echo e($o['fullname']); ?></td>
                <td><?php echo e($o['email']); ?></td>
                <td><?php echo e($o['phone']); ?></td>
                <td><?php echo e($o['service']); ?> <?php if(!empty($o['otherservice'])) echo ' / '.e($o['otherservice']); ?></td>
                <td><?php echo e($o['status'] ?? 'new'); ?></td>
                <td>
                  <form method="post" class="inline">
                    <input type="hidden" name="csrf" value="<?php echo e($csrf); ?>">
                    <button class="btn" name="mark_order" value="<?php echo $o['id']; ?>">Mark Processed</button>
                  </form>
                  <form method="post" class="inline" onsubmit="return confirm('Unataka kufuta order hii?')">
                    <input type="hidden" name="csrf" value="<?php echo e($csrf); ?>">
                    <button class="btn danger" name="delete_order" value="<?php echo $o['id']; ?>">Delete</button>
                  </form>
                  <button onclick="showDetails(<?php echo json_encode($o); ?>)" style="margin-left:6px">View</button>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
        <?php else: ?>
          <p class="small">Hakuna order mpya.</p>
        <?php endif; ?>
      </div>
    </div>

  </div>

  <script>
    function populate(obj){
      // obj arrives as an object because PHP json_encode used
      document.getElementById('proj_id').value = obj.id || '';
      document.getElementById('proj_title').value = obj.title || '';
      document.getElementById('proj_desc').value = obj.description || '';
      document.getElementById('proj_cat').value = obj.category || 'completed';
      window.scrollTo({top:0,behavior:'smooth'});
    }
    function clearForm(){
      document.getElementById('proj_id').value = '';
      document.getElementById('proj_title').value = '';
      document.getElementById('proj_desc').value = '';
      document.getElementById('proj_cat').value = 'completed';
    }
    function showDetails(o){
      let body = 'Fullname: ' + (o.fullname || '') + '\nEmail: ' + (o.email||'') + '\nPhone: ' + (o.phone||'') + '\nService: ' + (o.service||'') + '\nOthers: ' + (o.otherservice||'') + '\nDetails: ' + (o.details||'');
      alert(body);
    }
  </script>
</body>
</html>

<?php $conn->close(); ?>

