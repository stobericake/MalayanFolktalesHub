<?php
session_start();
include '../dbconn.php';

header('Content-Type: application/json');

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate input
    if (isset($data['folktaleID']) && isset($data['userID'])) {
        $folktaleID = intval($data['folktaleID']);
        $userID = intval($data['userID']);

        // Begin transaction
        $conn->begin_transaction();

        try {
            // Delete from the `bookmark` table
            $deleteBookmarkSQL = "DELETE FROM bookmark WHERE userID = ? AND bookmark_folktaleID = (SELECT bookmark_folktaleID FROM bookmark_folktale WHERE folktaleID = ?)";
            $stmt = $conn->prepare($deleteBookmarkSQL);
            $stmt->bind_param('ii', $userID, $folktaleID);
            $stmt->execute();

            // Check if any rows were deleted
            if ($stmt->affected_rows > 0) {
                // Decrement the count in `bookmark_folktale`
                $updateCountSQL = "UPDATE bookmark_folktale SET count = count - 1 WHERE folktaleID = ?";
                $updateStmt = $conn->prepare($updateCountSQL);
                $updateStmt->bind_param('i', $folktaleID);
                $updateStmt->execute();

                // Commit the transaction
                $conn->commit();

                echo json_encode(['success' => true, 'message' => 'Bookmark deleted successfully.']);
            } else {
                // No rows deleted, rollback
                $conn->rollback();
                echo json_encode(['success' => false, 'message' => 'No bookmark found to delete.']);
            }
        } catch (Exception $e) {
            // Rollback on error
            $conn->rollback();
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid input.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>