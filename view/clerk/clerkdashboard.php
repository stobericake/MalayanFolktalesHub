<?php
include '../dbconn.php';

$queryG = "SELECT genre, count(folktaleID) AS count FROM folktales GROUP BY genre ORDER BY count DESC LIMIT 5";
$resultG = mysqli_query($conn, $queryG);
$genre = [];
$genrecount = [];

while ($row = mysqli_fetch_assoc($resultG)) {
    $genre[] = $row['genre'];
    $genrecount[] = $row['count'];
}

$queryS = "SELECT state, count(folktaleID) AS count FROM folktales GROUP BY state ORDER BY count DESC LIMIT 5";
$resultS = mysqli_query($conn, $queryS);
$state = [];
$stateCount = [];

while ($row = mysqli_fetch_assoc($resultS)) {
    $state[] = $row['state'];
    $stateCount[] = $row['count'];
}
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
            background: linear-gradient(rgba(78, 78, 78, 0.7), rgba(78, 78, 78, 0.7)), url('../../assets/background.png') no-repeat center center/cover;
            background-size: cover;
        }
        .content {
            padding-top: 120px;
            margin-left: 30px;
            margin-bottom: 80px;
        }
        .top-section {
            width: 100%;
            height: 150px;
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
            text-decoration: none;
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
            height: 50px;
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
            margin-top: 20px;
            margin-bottom: 20px;
        }
            
        .chart-toggle {
            display: flex;
            border-radius: 10px 10px 0 0;
            background-color: #f0f0f0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .tab-button {
            flex: 1;
            padding: 10px 20px;
            border: none;
            background-color: transparent;
            color: #333;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .tab-button:hover {
            background-color: #ddd;
        }

        .tab-button.active {
            background-color:rgb(147, 196, 229);
            color: white;
        }

        .tab-button:first-child {
            border-radius: 10px 0 0 0;
        }

        .tab-button:last-child {
            border-radius: 0 10px 0 0;
        }
        canvas {
            background: #fff;
            border-radius:0 0 10px 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <?php include '../header.php'; ?>
    <!-- Content Section -->
    <div class="content">
        <div class="container">
            <div class="card-section">
                <a href="addfolktales.php" class="card">
                    <i class="bi bi-file-earmark-plus-fill icon-pic"></i>
                    <h3>ADD FOLKTALES</h3>
                </a>

                <a href="clerklisting.php" class="card">
                    <i class="bi bi-pen-fill icon-pic"></i>
                    <h3>UPDATE FOLKTALES</h3>
                </a>
            </div>

            <div class="chart-section">
                <div class="chart-toggle">
                    <button id="genreButton" class="tab-button active">View Genre Chart</button>
                    <button id="stateButton" class="tab-button">View State Chart</button>
                </div>
                <canvas id="salesChart" width="1300" height="400"></canvas>
            </div>
        </div>
    </div>

    <script>
        const genreLabels = <?php echo json_encode($genre); ?>;
        const genreCounts = <?php echo json_encode($genrecount); ?>;
        const stateLabels = <?php echo json_encode($state); ?>;
        const stateCounts = <?php echo json_encode($stateCount); ?>;

        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: genreLabels,
                datasets: [{
                    label: 'Count',
                    data: genreCounts,
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 99, 132, 0.2)'
                    ],
                    borderColor: [
                        'rgb(75, 192, 192)',
                        'rgb(255, 159, 64)',
                        'rgb(54, 162, 235)',
                        'rgb(153, 102, 255)',
                        'rgb(255, 99, 132)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Count'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Genre'
                        }
                    }
                }
            }
        });

        document.getElementById('genreButton').addEventListener('click', function() {
            salesChart.data.labels = genreLabels;
            salesChart.data.datasets[0].data = genreCounts;
            salesChart.data.datasets[0].label = 'Genre Count';
            salesChart.options.scales.x.title.text = 'Genre';
            salesChart.update();
            this.classList.add('active');
            document.getElementById('stateButton').classList.remove('active');
        });

        document.getElementById('stateButton').addEventListener('click', function() {
            salesChart.data.labels = stateLabels;
            salesChart.data.datasets[0].data = stateCounts;
            salesChart.data.datasets[0].label = 'State Count';
            salesChart.options.scales.x.title.text = 'State';
            salesChart.update();
            this.classList.add('active');
            document.getElementById('genreButton').classList.remove('active');
        });
    </script>
</body>
</html>