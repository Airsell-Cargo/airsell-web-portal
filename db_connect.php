<?php
$servername = "localhost";   // stays as localhost for XAMPP
$username   = "root";        // default XAMPP user
$password   = "";            // leave empty unless you set one
$database   = "airsell_cargo_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
