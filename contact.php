<?php
session_start();
require_once 'config/database.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $data = [
            'name' => trim($_POST["name"]),
            'email' => trim($_POST["email"]),
            'phone' => trim($_POST["phone"]),
            'subject' => trim($_POST["subject"]),
            'message' => trim($_POST["message"])
        ];
        
        // Validate required fields
        foreach ($data as $field => $value) {
            if (empty($value)) {
                $_SESSION['error'] = "All fields are required!";
                header("Location: contact.php");
                exit();
            }
        }
        
        // Insert into Supabase
        $commentId = Database::insert('comments', $data);
        
        if ($commentId) {
            $_SESSION['success'] = "Message sent successfully! We'll get back to you soon.";
            
            // Optional: Send email notification
            // $this->sendEmailNotification($data);
            
        } else {
            $_SESSION['error'] = "Failed to send message. Please try again!";
        }
        
    } catch (Exception $e) {
        error_log("Contact form error: " . $e->getMessage());
        $_SESSION['error'] = "An error occurred. Please try again!";
    }
    
    header("Location: contact.php");
    exit();
}

// [The rest of your contact.php HTML/CSS remains exactly the same]
?>