<?php
// Database connection settings
$servername = "localhost";   // XAMPP default
$username = "root";          // XAMPP default
$password = "";              // XAMPP default (usually empty)
$dbname = "govt_scheme";     // Make sure this matches your database name

// Create connection
$conn = new mysqli("localhost","root","","govt_scheme");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
