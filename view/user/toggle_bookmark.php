<?php
session_start();
include '../dbconn.php';

if (!isset($_SESSION['userID'])) {
    die("User not logged in.");
}

$userID = $_SESSION['userID'];
$folktaleID = $_POST['folktaleID'];

$conn->begin_transaction();

try {
    $getBookmarkFolktaleID = $conn->prepare("SELECT bookmark_folktaleID FROM bookmark_folktale WHERE folktaleID = ?");
    $getBookmarkFolktaleID->bind_param("i", $folktaleID);
    $getBookmarkFolktaleID->execute();
    $getBookmarkFolktaleID->store_result();
    $getBookmarkFolktaleID->bind_result($bookmark_folktaleID);
    $getBookmarkFolktaleID->fetch();

    if ($getBookmarkFolktaleID->num_rows == 0) {
        $addBookmarkFolktale = $conn->prepare("INSERT INTO bookmark_folktale (folktaleID, count) VALUES (?, 1)");
        $addBookmarkFolktale->bind_param("i", $folktaleID);
        $addBookmarkFolktale->execute();
        $bookmark_folktaleID = $conn->insert_id;
    }

    $checkBookmark = $conn->prepare("SELECT bookmarkID FROM bookmark WHERE userID = ? AND bookmark_folktaleID = ?");
    $checkBookmark->bind_param("ii", $userID, $bookmark_folktaleID);
    $checkBookmark->execute();
    $checkBookmark->store_result();

    if ($checkBookmark->num_rows > 0) {
        // Folktale is already bookmarked, so remove it
        $removeBookmark = $conn->prepare("DELETE FROM bookmark WHERE userID = ? AND bookmark_folktaleID = ?");
        $removeBookmark->bind_param("ii", $userID, $bookmark_folktaleID);
        $removeBookmark->execute();

        // Decrement the count in bookmark_folktale
        $updateCount = $conn->prepare("UPDATE bookmark_folktale SET count = count - 1 WHERE folktaleID = ?");
        $updateCount->bind_param("i", $folktaleID);
        $updateCount->execute();

        echo "removed";
    } else {
        // Folktale is not bookmarked, so add it
        $addBookmark = $conn->prepare("INSERT INTO bookmark (userID, bookmark_folktaleID) VALUES (?, ?)");
        $addBookmark->bind_param("ii", $userID, $bookmark_folktaleID);
        $addBookmark->execute();

        // Increment the count in bookmark_folktale
        $updateCount = $conn->prepare("UPDATE bookmark_folktale SET count = count + 1 WHERE folktaleID = ?");
        $updateCount->bind_param("i", $folktaleID);
        $updateCount->execute();

        echo "added";
    }
    $conn->commit();
} catch (Exception $e) {
    $conn->rollback();
    die("Error: " . $e->getMessage());
}

$getBookmarkFolktaleID->close();
$checkBookmark->close();
$conn->close();
?>