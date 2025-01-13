<?php
session_start();

// Database connection
include 'dbconn.php';
if (!isset($_SESSION['role'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit;
}

$role = $_SESSION['role'];

// Fetch unique genres
$genres_query = "SELECT DISTINCT genre FROM folktales";
$genres_result = mysqli_query($conn, $genres_query);

// Fetch unique states
$states_query = "SELECT DISTINCT state FROM folktales";
$states_result = mysqli_query($conn, $states_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Home Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .genre-state-panel {
            background-color: #C3DFE6;
            padding: 10px;
            border-radius: 10px;
            height: 100%;
        }

        .genre-state-panel button {
            width: 100%;
            margin-bottom: 10px;
            background-color: #fff3e0;
            border: none;
            border-radius: 5px;
            padding: 10px;
        }

    </style>
</head>
<body>
<div class="d-flex">
    <!-- Include Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="container-fluid p-5">
        <h3 class="mb-4">User Home Page</h3>
        <div class="row">
            <!-- Genre Panel -->
            <div class="col-md-5 genre-state-panel">
                <h5>FOLKTALE GENRE</h5>
                <?php
                if ($genres_result && mysqli_num_rows($genres_result) > 0) {
                    while ($row = mysqli_fetch_assoc($genres_result)) {
                        echo "<button onclick=\"filterFolktales('genre', '" . htmlspecialchars($row['genre']) . "')\">" 
                            . htmlspecialchars($row['genre']) . 
                            "</button>";
                    }
                } else {
                    echo "<p>No genres available.</p>";
                }
                ?>
            </div>

            <div class="col-md-1"></div>

            <!-- State Panel -->
            <div class="col-md-5 genre-state-panel">
                <h5>FOLKTALE STATE</h5>
                <?php
                if ($states_result && mysqli_num_rows($states_result) > 0) {
                    while ($row = mysqli_fetch_assoc($states_result)) {
                        echo "<button onclick=\"filterFolktales('state', '" . htmlspecialchars($row['state']) . "')\">" 
                            . htmlspecialchars($row['state']) . 
                            "</button>";
                    }
                } else {
                    echo "<p>No states available.</p>";
                }
                ?>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Filtering -->
<script>
    function filterFolktales(filterType, value) {
        // Redirect to a page or call an API to display filtered folktales
        location.href = `filter_folktales.php?type=${filterType}&value=${value}`;
    }
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
