<?php
// Supabase Configuration
class SupabaseConfig {
    // Supabase Project Details
    public static $supabaseUrl = 'https://your-project-id.supabase.co';
    public static $supabaseKey = 'your-anon-public-key';
    public static $supabaseServiceKey = 'your-service-role-key';
    
    // PostgreSQL Connection for direct database access
    public static $dbHost = 'db.your-project-id.supabase.co';
    public static $dbPort = '5432';
    public static $dbName = 'postgres';
    public static $dbUser = 'postgres';
    public static $dbPass = 'your-database-password';
    
    // Storage Configuration
    public static $storageBucket = 'project-images';
    
    // JWT Secret for authentication
    public static $jwtSecret = 'your-jwt-secret';
}

// Helper function for JSON responses
function jsonResponse($success, $data = [], $message = '') {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

