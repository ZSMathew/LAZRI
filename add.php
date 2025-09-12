<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];

    // handle image upload
    $imagePath = null;
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
        $fileName = time() . "_" . basename($_FILES['image']['name']);
        $targetFile = $targetDir . $fileName;
        move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
        $imagePath = $targetFile;
    }

    $sql = "INSERT INTO projects (title, description, category, image_path) VALUES (?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $title, $description, $category, $imagePath);

    if ($stmt->execute()) {
        header("Location: admin.php?msg=added");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
