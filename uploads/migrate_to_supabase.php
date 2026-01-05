<?php
/**
 * Migration Script: MySQL to Supabase
 * Run this script once to migrate existing data
 */

require_once 'config/database.php';

class Migrator {
    private $mysqlConn;
    private $supabase;
    
    public function __construct() {
        // Old MySQL connection
        $this->mysqlConn = new mysqli('localhost', 'root', '', 'lazri');
        if ($this->mysqlConn->connect_error) {
            die("MySQL Connection failed: " . $this->mysqlConn->connect_error);
        }
        
        // New Supabase connection
        $this->supabase = Database::connect();
    }
    
    public function migrateProjects() {
        echo "Migrating projects...\n";
        
        $result = $this->mysqlConn->query("SELECT * FROM projects");
        $count = 0;
        
        while ($row = $result->fetch_assoc()) {
            $data = [
                'title' => $row['title'],
                'description' => $row['description'],
                'category' => $row['category'],
                'image' => $row['image'],
                'created_at' => $row['created_at']
            ];
            
            try {
                Database::insert('projects', $data);
                $count++;
            } catch (Exception $e) {
                echo "Error migrating project {$row['id']}: " . $e->getMessage() . "\n";
            }
        }
        
        echo "Migrated {$count} projects\n";
        return $count;
    }
    
    public function migrateOrders() {
        echo "Migrating orders...\n";
        
        $result = $this->mysqlConn->query("SELECT * FROM orders");
        $count = 0;
        
        while ($row = $result->fetch_assoc()) {
            $data = [
                'fullname' => $row['fullname'],
                'email' => $row['email'],
                'phone' => $row['phone'],
                'service' => $row['service'],
                'otherservice' => $row['otherservice'],
                'details' => $row['details'],
                'status' => $row['status'] ?? 'pending',
                'created_at' => $row['created_at']
            ];
            
            try {
                Database::insert('orders', $data);
                $count++;
            } catch (Exception $e) {
                echo "Error migrating order {$row['id']}: " . $e->getMessage() . "\n";
            }
        }
        
        echo "Migrated {$count} orders\n";
        return $count;
    }
    
    public function migrateComments() {
        echo "Migrating comments...\n";
        
        $result = $this->mysqlConn->query("SELECT * FROM comments");
        $count = 0;
        
        while ($row = $result->fetch_assoc()) {
            $data = [
                'name' => $row['name'],
                'email' => $row['email'],
                'phone' => $row['phone'],
                'subject' => $row['subject'],
                'message' => $row['message'],
                'reply' => $row['reply'],
                'created_at' => $row['created_at']
            ];
            
            try {
                Database::insert('comments', $data);
                $count++;
            } catch (Exception $e) {
                echo "Error migrating comment {$row['id']}: " . $e->getMessage() . "\n";
            }
        }
        
        echo "Migrated {$count} comments\n";
        return $count;
    }
    
    public function runAll() {
        echo "Starting migration...\n";
        echo "========================\n";
        
        $projects = $this->migrateProjects();
        $orders = $this->migrateOrders();
        $comments = $this->migrateComments();
        
        echo "========================\n";
        echo "Migration completed!\n";
        echo "Total migrated: {$projects} projects, {$orders} orders, {$comments} comments\n";
    }
}

// Run migration
if (php_sapi_name() === 'cli') {
    $migrator = new Migrator();
    $migrator->runAll();
} else {
    echo "<h2>Migration Script</h2>";
    echo "<p>Run this script from command line:</p>";
    echo "<pre>php migrate_to_supabase.php</pre>";
    echo "<p>Or <a href='javascript:void(0)' onclick='runMigration()'>click here</a> to run in browser (not recommended for large datasets).</p>";
    
    if (isset($_GET['run']) && $_GET['run'] === '1') {
        ob_start();
        $migrator = new Migrator();
        $migrator->runAll();
        $output = ob_get_clean();
        echo "<pre>" . htmlspecialchars($output) . "</pre>";
    }
}
?>