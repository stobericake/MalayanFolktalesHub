<?php
session_start();
include '../dbconn.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $userID = $_SESSION['userID'];
    $phone = $data['phone_number'];
    $password = $data['password'];

    // Validate input
    if (empty($phone) && empty($password)) {
        echo json_encode(['success' => false, 'message' => 'No changes detected.']);
        exit;
    }

    // Update phone number and/or password
    $query = "UPDATE user SET ";
    $updates = [];
    if (!empty($phone)) {
        $updates[] = "phone_number = '$phone'";
    }
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $updates[] = "password = '$hashedPassword'";
    }
    $query .= implode(", ", $updates) . " WHERE userID = '$userID'";

    if (mysqli_query($conn, $query)) {
        echo json_encode(['success' => true, 'message' => 'Profile updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update profile.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>