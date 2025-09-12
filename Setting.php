
<?php include 'connection.php'; ?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setting</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f4f6f9;
            color: #333;
            { font-family: Arial, sans-serif; background:#f4f4f4; padding:20px; }
    .container { max-width: 900px; margin:auto; background:white; padding:20px; border-radius:8px; }
    h1 { margin-bottom:20px; }
    form { margin-bottom:30px; }
    input, textarea, select { width:100%; padding:8px; margin:6px 0; }
    button { padding:10px 15px; margin-top:10px; }
    table { width:100%; border-collapse: collapse; margin-top:20px; }
    table, th, td { border:1px solid #ccc; }
    th, td { padding:10px; text-align:left; }
    img { max-width:80px; }
        }

        /* Header */
        #header {
            background: #fff;
            padding: 15px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        #header h1 {
            color: #2563eb;
            font-size: 24px;
            font-weight: bold;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
        }

        nav ul li a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: 0.3s;
        }

        nav ul li a:hover {
            color: #2563eb;
        }

        /* image */
        img {
            width: 4rem;
            height: 4rem;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header id="header">
        <h1><img src="./images/Logo.png"></h1>
        <!-- <nav>
            <ul>
                <li><a href="#">Nyumbani</a></li>
                <li><a href="#">Kuhusu</a></li>
                <li><a href="#">Huduma</a></li>
                <li><a href="#">Projects</a></li>
                <li><a href="#">Timu</a></li>
                <li><a href="#">Mawasiliano</a></li> -->
                <span>Setting</span>
            <!-- </ul>
        </nav> -->
    </header>

    <div class="container">
  <h1>Manage Projects</h1>

  <form action="add.php" method="POST" enctype="multipart/form-data">
    <h3>Add New Project</h3>
    <input type="text" name="title" placeholder="Title" required>
    <textarea name="description" placeholder="Description"></textarea>
    <select name="category">
      <option value="web">Web</option>
      <option value="mobile">Mobile</option>
      <option value="research">Research</option>
      <option value="other">Other</option>
    </select>
    <input type="file" name="image">
    <button type="submit">Add Project</button>
  </form>

  <h3>Existing Projects</h3>
  <table>
    <tr><th>Image</th><th>Title</th><th>Category</th><th>Actions</th></tr>
    <?php
      $result = $conn->query("SELECT * FROM projects ORDER BY created_at DESC");
      while($row = $result->fetch_assoc()){
        echo "<tr>
          <td><img src='{$row['image_path']}'></td>
          <td>{$row['title']}</td>
          <td>{$row['category']}</td>
          <td>
            <form action='update.php' method='POST' enctype='multipart/form-data' style='display:inline-block;'>
              <input type='hidden' name='id' value='{$row['id']}'>
              <input type='hidden' name='old_image' value='{$row['image_path']}'>
              <input type='text' name='title' value='{$row['title']}'>
              <input type='text' name='description' value='{$row['description']}'>
              <select name='category'>
                <option value='web' ".($row['category']=='web'?'selected':'').">Web</option>
                <option value='mobile' ".($row['category']=='mobile'?'selected':'').">Mobile</option>
                <option value='research' ".($row['category']=='research'?'selected':'').">Research</option>
                <option value='other' ".($row['category']=='other'?'selected':'').">Other</option>
              </select>
              <input type='file' name='image'>
              <button type='submit'>Update</button>
            </form>
            <a href='delete.php?id={$row['id']}' onclick='return confirm(\"Delete this?\")'>Delete</a>
          </td>
        </tr>";
      }
    ?>
  </table>
</div>
</body> 
</html>