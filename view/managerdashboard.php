<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
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
    </style>
</head>
<body>
    <div class="container">
        <!-- Top Section -->
        <div class="top-section"></div>

        <!-- Card Section -->
        <div class="card-section">
            <!-- Card 1: User Report -->
            <a href="user-report.php" class="card">
                <img src="user-icon.png" >
                <h3>USER REPORT</h3>
            </a>

            <!-- Card 2: Folktales Origin -->
            <a href="folktales-origin.php" class="card">
                <img src="location-icon.png" >
                <h3>FOLKTALES ORIGIN</h3>
            </a>

            <!-- Card 3: Folktales Genre -->
            <a href="folktales-genre.php" class="card">
                <img src="calendar-icon.png" >
                <h3>FOLKTALES GENRE</h3>
            </a>
        </div>
    </div>
</body>
</html>
