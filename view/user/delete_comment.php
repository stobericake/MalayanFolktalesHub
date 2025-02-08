<?php
session_start(); 
include '../dbconn.php';

$commentID = isset($_GET['commentID']) ? intval($_GET['commentID']) : 0;
$userID = isset($_SESSION['userID']) ? $_SESSION['userID'] : null;

header('Content-Type: application/json');

if ($commentID > 0 && $userID) {
    $checkQuery = "SELECT userID FROM comment WHERE commentID = ?";
    $checkStmt = $conn->prepare($checkQuery);
    if (!$checkStmt) {
        echo json_encode(['success' => false, 'error' => 'Prepare failed: ' . $conn->error]);
        exit;
    }
    $checkStmt->bind_param("i", $commentID);
    if (!$checkStmt->execute()) {
        echo json_encode(['success' => false, 'error' => 'Execute failed: ' . $checkStmt->error]);
        exit;
    }
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        $comment = $checkResult->fetch_assoc();
        if ($comment['userID'] == $userID) {
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
            echo json_encode(['success' => false, 'error' => 'You do not have permission to delete this comment.']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Comment not found.']);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid comment ID or user not logged in.']);
    exit;
}
?>