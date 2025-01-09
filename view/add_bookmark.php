<?php
session_start();
include 'dbconn.php';

header('Content-Type: application/json');

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate input
    if (isset($data['folktalesID']) && isset($data['userID'])) {
        $folktalesID = intval($data['folktalesID']);
        $userID = intval($data['userID']);

        // Insert into the `bookmark` table
        $bookmarkSQL = "INSERT INTO bookmark (userID) VALUES (?)";
        $stmt = $conn->prepare($bookmarkSQL);
        $stmt->bind_param('i', $userID);

        if ($stmt->execute()) {
            $bookmarkID = $conn->insert_id;

            // Insert into the `bookmark_folktales` table
            $bookmarkFolktalesSQL = "INSERT INTO bookmark_folktale (bookmarkID, folktalesID) VALUES (?, ?)";
            $stmt = $conn->prepare($bookmarkFolktalesSQL);
            $stmt->bind_param('ii', $bookmarkID, $folktalesID);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Folktale added to bookmarks.']);
                exit;
            }
        }
        echo json_encode(['success' => false, 'message' => 'Failed to add folktale to bookmarks.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid input.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>

