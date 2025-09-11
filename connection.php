<?php
$servername = "localhost";   // server
$username   = "root";        // default MySQL user
$password   = "";            // weka password yako ya MySQL kama ipo
$dbname     = "LAZRI";       // jina la database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>