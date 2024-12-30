<?php
// Database connection configuration
$host = 'localhost'; // Database host
$dbname = 'malayanfolktales_hub'; // Database name
$username = 'root'; // Database username
$password = ''; // Database password

try {
    // Create a connection to the database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle connection failure
    echo "Database connection failed: " . $e->getMessage();
}
?>