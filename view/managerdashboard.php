<?php
session_start();

// Database connection
include 'dbconn.php';


// Fetch total origin of folktales
$totalOriginResult = $conn->query("SELECT COUNT(DISTINCT state) AS total_origin FROM folktales");
$totalOriginRow = $totalOriginResult->fetch_assoc();
$totalOrigins = $totalOriginRow['total_origin'];

// Fetch total user
$totalUserResult = $conn->query("SELECT COUNT(*) as total_user FROM user");
$totalUserRow = $totalUserResult->fetch_assoc();
$totalUser = $totalUserRow['total_user'];

// Fetch total user
$totalGenreResult = $conn->query("SELECT COUNT(DISTINCT genre) AS genre_count FROM folktales");
$totalGenreRow = $totalGenreResult->fetch_assoc();
$totalGenre = $totalGenreRow['genre_count'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: url('background.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 80%;
            background-color: #C3DFE6;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .top-section {
            width: 100%;
            height: 200px;
            background-color: #f5f5f5;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .card-section {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .card {
            flex: 1;
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .card img {
            width: 60px;
            height: 60px;
            margin-bottom: 10px;
        }

        .card h3 {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        .card a {
            text-decoration: none;
            color: inherit;
        }
        .icon-pic {
            font-size: 80px;
            color: black;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Top Section -->
        <div class="top-section"></div>

        <!-- Card Section -->
        <div class="card-section">
            <!-- Card 1: User Report -->
            <!--<img src="../year.png" alt="Calendar Icon"> -->
            <a href="user-report.php" class="card">
                <i class="bi bi-person-heart icon-pic"></i>
                <h3>USER REPORT</h3>
                <p class="total">Total: <span id="genre-total"><?php echo $totalUser; ?></span></p>
            </a>

            <!-- Card 2: Folktales Origin -->
             <!--<img src="../year.png" alt="Calendar Icon"> -->
            <a href="folktales-origin.php" class="card">
            <i class="bi bi-geo-fill icon-pic"></i>
                <h3>FOLKTALES ORIGIN</h3>
                <p class="total">Total: <span id="genre-total"><?php echo $totalOrigins; ?></span></p>
            </a>

            <!-- Card 3: Folktales Genre -->
             <!--<img src="../year.png" alt="Calendar Icon"> -->
            <a href="folktales-genre.php" class="card">
            <i class="bi bi-journal icon-pic"></i>
                <h3>FOLKTALES GENRE</h3>
                <p class="total">Total: <span id="genre-total"><?php echo $totalGenre; ?></span></p>
            </a>
        </div>
    </div>
</body>
</html>
