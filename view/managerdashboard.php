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

// Fetch total genre
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: url('../assets/background.png') no-repeat center center/cover;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
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
        .card:nth-child(1) {
            background-color: #f9ebea;
        }

        .card:nth-child(2) {
            background-color: #e8f8f5;
        }

        .card:nth-child(3) {
            background-color: #fef9e7;
        }


        .icon-pic {
            font-size: 80px;
            color: black;
        }

        .chart-section {
            margin-top: 30px;
        }

        canvas {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        

        <!-- Card Section -->
        <div class="card-section">
            <!-- Card 1: User Report -->
            <a href="user-report.php" class="card">
                <i class="bi bi-person-heart icon-pic"></i>
                <h3>USER REPORT</h3>
                <p class="total">Total: <span id="genre-total"><?php echo $totalUser; ?></span></p>
            </a>

            <!-- Card 2: Folktales Origin -->
            <a href="folktales-origin.php" class="card">
                <i class="bi bi-geo-fill icon-pic"></i>
                <h3>FOLKTALES ORIGIN</h3>
                <p class="total">Total: <span id="genre-total"><?php echo $totalOrigins; ?></span></p>
            </a>

            <!-- Card 3: Folktales Genre -->
            <a href="folktales-genre.php" class="card">
                <i class="bi bi-journal icon-pic"></i>
                <h3>FOLKTALES GENRE</h3>
                <p class="total">Total: <span id="genre-total"><?php echo $totalGenre; ?></span></p>
            </a>
        </div>

        <!-- Chart Section -->
        <div class="chart-section">
            <canvas id="salesChart" width="1300" height="400"></canvas>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Red', 'Orange', 'Yellow', 'Green', 'Blue', 'Purple', 'Grey'],
                datasets: [{
                    label: 'Bookmarks',
                    data: [32, 45, 67, 23, 89, 45, 12],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(201, 203, 207, 0.2)'
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(54, 162, 235)',
                        'rgb(153, 102, 255)',
                        'rgb(201, 203, 207)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
