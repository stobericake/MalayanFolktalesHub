<?php
session_start();
include '../dbconn.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Invalid folktale ID!";
    exit;
}

$folktaleId = $_GET['id'];

try {
    $query = "DELETE FROM folktales WHERE folktaleID = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        throw new Exception("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("s", $folktaleId);
    if ($stmt->execute()) {
        echo "success";
    } else {
        throw new Exception("Error executing query: " . $stmt->error);
    }
} catch (Exception $e) {
    echo $e->getMessage();
}

$stmt->close();
$conn->close();
?>
