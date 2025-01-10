<?php
// Start session
session_start();

// Include database connection
include 'dbconn.php';

// Check if the ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Invalid folktale ID!";
    exit;
}

$folktaleId = $_GET['id'];

try {
    // Prepare the DELETE query
    $query = "DELETE FROM folktales WHERE folktaleID = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        throw new Exception("Error preparing statement: " . $conn->error);
    }

    // Bind parameter and execute
    $stmt->bind_param("s", $folktaleId);
    if ($stmt->execute()) {
        echo "success"; // Output "success" for AJAX to handle
    } else {
        throw new Exception("Error executing query: " . $stmt->error);
    }
} catch (Exception $e) {
    echo $e->getMessage();
}

// Close resources
$stmt->close();
$conn->close();
?>
