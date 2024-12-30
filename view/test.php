<?php
// Include the database connection file
require 'dbconn.php';

// Check the connection (optional)
if ($conn) {
    echo "Connected successfully!";
}

// Example query
$query = "SELECT * FROM user";
$stmt = $conn->query($query);

// Fetch and display results
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "User ID: " . $row['userID'] . " - Name: " . $row['name'] . "<br>";
}
?>