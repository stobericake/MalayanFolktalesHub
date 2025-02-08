<?php
include 'dbconn.php';

$type = $_GET['type'];
$value = $_GET['value']; 

$query = "SELECT folktaleID, title FROM folktales WHERE $type = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 's', $value);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$id = [];
$titles = [];
while ($row = mysqli_fetch_assoc($result)) {
    $titles[] = $row;
}

echo json_encode($titles);
?>