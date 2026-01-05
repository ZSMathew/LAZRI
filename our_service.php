<?php
session_start();
require_once 'config/database.php';

// Handle order submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $data = [
            'fullname' => $_POST['fullname'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone'],
            'service' => $_POST['services'],
            'otherservice' => $_POST['otherservice'] ?? '',
            'details' => $_POST['details']
        ];
        
        $orderId = Database::insert('orders', $data);
        
        if ($orderId) {
            $_SESSION['order_success'] = true;
            header("Location: our_service.php?success=1");
            exit();
        } else {
            header("Location: our_service.php?success=0");
            exit();
        }
        
    } catch (Exception $e) {
        error_log("Order submission error: " . $e->getMessage());
        header("Location: our_service.php?success=0");
        exit();
    }
}

// [The rest of your our_service.php HTML/CSS remains exactly the same]
?>