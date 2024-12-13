<?php
// Database connection
$servername = "localhost";
$username = "root";  // Default username for XAMPP
$password = "pass";      // Default password for XAMPP
$dbname = "fitness_tracker_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
