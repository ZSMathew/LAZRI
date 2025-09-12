<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];

    // Check if new image uploaded
    $imagePath = $_POST['old_image'];
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
        $fileName = time() . "_" . basename($_FILES['image']['name']);
        $targetFile = $targetDir . $fileName;
        move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
        $imagePath = $targetFile;
    }

    $sql = "UPDATE projects SET title=?, description=?, category=?, image_path=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $title, $description, $category, $imagePath, $id);

    if ($stmt->execute()) {
        header("Location: admin.php?msg=updated");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
