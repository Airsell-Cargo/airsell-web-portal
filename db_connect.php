<?php
/**
 * Database Connection Handler
 * Uses environment variables for security
 * Implements proper error handling and charset configuration
 */

// Load credentials from environment variables
$servername = getenv('DB_HOST') ?: 'localhost';
$username   = getenv('DB_USER') ?: 'root';
$password   = getenv('DB_PASSWORD') ?: '';
$database   = getenv('DB_NAME') ?: 'airsell_cargo_db';

// Set connection timeout (optional, in seconds)
$timeout = 5;

// Create connection with charset specification
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    // Log error securely (don't expose details to users)
    error_log('Database connection failed: ' . $conn->connect_error);
    
    // Show generic error message to user
    http_response_code(500);
    die('Database connection error. Please contact administrator.');
}

// Set charset to UTF-8 for proper encoding
$conn->set_charset("utf8mb4");

// Optional: Set timezone
date_default_timezone_set('UTC');

// Enable error reporting for mysqli
$conn->report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;
?>