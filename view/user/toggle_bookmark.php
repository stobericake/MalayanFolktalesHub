<?php
session_start();
include 'dbconn.php';

$response = ['success' => false, 'message' => ''];

if (!isset($_SESSION['userID'])) {
    $response['message'] = 'User not logged in.';
    echo json_encode($response);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$folktalesID = intval($data['folktalesID']);
$userID = intval($data['userID']);
$action = $data['action'];

if ($action === 'add') {
    // Add bookmark
    $insertQuery = "INSERT INTO bookmarks (userID, folktaleID) VALUES (?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("ii", $userID, $folktalesID);
    if ($stmt->execute()) {
        $response['success'] = true;
    } else {
        $response['message'] = 'Failed to add bookmark.';
    }
    $stmt->close();
} elseif ($action === 'remove') {
    // Remove bookmark
    $deleteQuery = "DELETE FROM bookmarks WHERE userID = ? AND folktaleID = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("ii", $userID, $folktalesID);
    if ($stmt->execute()) {
        $response['success'] = true;
    } else {
        $response['message'] = 'Failed to remove bookmark.';
    }
    $stmt->close();
}

// Get updated total bookmarks count
$totalBookmarksQuery = "SELECT COUNT(*) AS totalBookmarks FROM bookmarks WHERE folktaleID = ?";
$totalBookmarksStmt = $conn->prepare($totalBookmarksQuery);
$totalBookmarksStmt->bind_param("i", $folktalesID);
$totalBookmarksStmt->execute();
$totalBookmarksResult = $totalBookmarksStmt->get_result();
$totalBookmarksData = $totalBookmarksResult->fetch_assoc();
$response['totalBookmarks'] = $totalBookmarksData['totalBookmarks'];
$totalBookmarksStmt->close();

echo json_encode($response);
$conn->close();
?>