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

        // Delete from the `bookmark_folktale` table
        $deleteBookmarkFolktalesSQL = "DELETE FROM bookmark_folktale WHERE folktalesID = ? AND bookmarkID IN (SELECT bookmarkID FROM bookmark WHERE userID = ?)";
        $stmt = $conn->prepare($deleteBookmarkFolktalesSQL);
        $stmt->bind_param('ii', $folktalesID, $userID);

        if ($stmt->execute()) {
            // Check if any rows were deleted from bookmark_folktale
            if ($stmt->affected_rows > 0) {
                // Delete from the `bookmark` table if no other folktales are bookmarked by the user
                $deleteBookmarkSQL = "DELETE FROM bookmark WHERE userID = ? AND NOT EXISTS (SELECT 1 FROM bookmark_folktale WHERE bookmark_folktale.bookmarkID = bookmark.bookmarkID)";
                $stmt = $conn->prepare($deleteBookmarkSQL);
                $stmt->bind_param('i', $userID);

                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Folktale removed from bookmarks.']);
                    exit;
                }
            }
        }
        echo json_encode(['success' => false, 'message' => 'Failed to remove folktale from bookmarks.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid input.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
