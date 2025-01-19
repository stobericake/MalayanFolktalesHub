<?php
// Include the database connection
include '../dbconn.php';

// Get the search term from the request
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Fetch the folktales matching the search term
$sql = "SELECT * FROM folktales WHERE title LIKE ? OR state LIKE ? OR genre LIKE ?";
$stmt = $conn->prepare($sql);
$searchTerm = "%" . $search . "%";
$stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

// Collect the matching folktales
$folktales = [];
while ($row = $result->fetch_assoc()) {
    $folktales[] = $row;
}

// Return the results as JSON
echo json_encode($folktales);
?>
