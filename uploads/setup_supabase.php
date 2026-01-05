<?php
/**
 * Setup Script for Supabase
 * Creates necessary tables and initial data
 */

require_once 'config/database.php';

echo "<h2>Supabase Setup</h2>";

// Check connection
try {
    $pdo = Database::connect();
    echo "<p style='color: green'>✓ Connected to Supabase successfully</p>";
    
    // Create tables
    $sqlFiles = [
        'sql/create_tables.sql',
        'sql/seed_data.sql'
    ];
    
    foreach ($sqlFiles as $file) {
        if (file_exists($file)) {
            $sql = file_get_contents($file);
            $pdo->exec($sql);
            echo "<p style='color: green'>✓ Executed $file</p>";
        }
    }
    
    echo "<p style='color: green'>✓ Setup completed successfully!</p>";
    echo "<p><a href='admin_dashboard.php'>Go to Admin Dashboard</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red'>✗ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Please check your Supabase configuration in config/supabase_config.php</p>";
}
?>