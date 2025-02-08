<?php
session_start();
include '../dbconn.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['folktaleID']) && isset($data['userID'])) {
        $folktaleID = intval($data['folktaleID']);
        $userID = intval($data['userID']);
        $conn->begin_transaction();

        try {
            $deleteBookmarkSQL = "DELETE FROM bookmark WHERE userID = ? AND bookmark_folktaleID = (SELECT bookmark_folktaleID FROM bookmark_folktale WHERE folktaleID = ?)";
            $stmt = $conn->prepare($deleteBookmarkSQL);
            $stmt->bind_param('ii', $userID, $folktaleID);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $updateCountSQL = "UPDATE bookmark_folktale SET count = count - 1 WHERE folktaleID = ?";
                $updateStmt = $conn->prepare($updateCountSQL);
                $updateStmt->bind_param('i', $folktaleID);
                $updateStmt->execute();

                $conn->commit();

                echo json_encode(['success' => true, 'message' => 'Bookmark deleted successfully.']);
            } else {
                $conn->rollback();
                echo json_encode(['success' => false, 'message' => 'No bookmark found to delete.']);
            }
        } catch (Exception $e) {
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