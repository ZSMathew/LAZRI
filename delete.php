<?php
include 'connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // delete image
    $result = $conn->query("SELECT image_path FROM projects WHERE id=$id");
    $row = $result->fetch_assoc();
    if ($row['image_path'] && file_exists($row['image_path'])) {
        unlink($row['image_path']);
    }

    $sql = "DELETE FROM projects WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: admin.php?msg=deleted");
}
?>
