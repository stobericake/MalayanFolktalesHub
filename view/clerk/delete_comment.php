<?php
session_start(); // Ensure session is started
include '../dbconn.php';

$commentID = isset($_GET['commentID']) ? intval($_GET['commentID']) : 0;
header('Content-Type: application/json'); // Set response type to JSON

if ($commentID > 0) {
    // Delete the comment
    $deleteQuery = "DELETE FROM comment WHERE commentID = ?";
    $deleteStmt = $conn->prepare($deleteQuery);
    if (!$deleteStmt) {
        echo json_encode(['success' => false, 'error' => 'Prepare failed: ' . $conn->error]);
        exit;
    }
    $deleteStmt->bind_param("i", $commentID);
    if ($deleteStmt->execute()) {
        echo json_encode(['success' => true]);
        exit;
    } else {
        echo json_encode(['success' => false, 'error' => 'Delete failed: ' . $deleteStmt->error]);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Comment not found.']);
    exit;
}

?>