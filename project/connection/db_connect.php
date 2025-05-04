<?php
$servername = "localhost"; // Change if using a remote server
$username = "root"; // Change to your MySQL username
$password = "Gooni218282"; // Change to your MySQL password
$database = "employee_management_system"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
