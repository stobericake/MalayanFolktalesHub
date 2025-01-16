<?php
// Database connection
include 'dbconn.php';

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
    <title>Landing Page</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            font-weight: bold;
            background-image: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('../assets/background.png');
            background-size: cover;
            background-position: 80%; /* Set the background image */
            height: 100vh;
        }

        .welcome-section h1 {
            font-size: 36px;
            font-weight: bold;
            color: #444;
            margin-bottom: 10px;
        }

        .welcome-section h2 {
            font-size: 20px;
            font-weight: 600;
            color: #0B2428;
            margin-bottom: 30px;
        }

        .content {
            padding-top: 200px;
            margin-left: 50px;
        }
        .content h3 {
            padding-top: 50px;
            font-size: 50px;
            color: #C3DFE6;
        }
        .content p {
            margin-top: 15px;
            color: #fff;
        }
        #btn {
            width: 100px;
            height: 36px;
            margin-top: 10px;
            background: #C3DFE6;
            border-radius: 10px;
            border: none;
            transition: 0.5s ease;
            cursor: pointer;
        }
        #btn:hover {
            background: #ffffff;
            color: #7a2005;
        }

        .genre-state-panel {
            background-color: #C3DFE6;
            padding: 10px;
            border-radius: 10px;
            height: 50%;
            margin-right: 30px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); /* Responsive grid */
            gap: 5px; /* Spacing between buttons */
        }
        .genre-state-panel button {
            background-color: #fff3e0;
            border: none;
            border-radius: 5px;
            padding: 10px;
            text-align: center;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .genre-state-panel button:hover {
            background-color: #ffffff;
            color: #7a2005;
        }
    </style>
</head>
<body class="font-sans text-gray-700">
    <?php include 'header.php'; ?>
    <!-- Content Section -->
    <div class="content">
        <div class="row">
            <div class="col-md-5">
                <h3>Welcome to <br> MalayanFolktalesHub</h3>
                <p>"Preserving the past, inspiring the future through the tales of our ancestors."</p>
                <?php
                // Check if user is logged in
                if (!isset($_SESSION['role'])) {
                    // User is not logged in, show Login and Register buttons
                    echo '<button onclick="window.location.href=\'login.php\';" type="button" id="btn">Login</button>';
                    echo '<button onclick="window.location.href=\'register.php\';" type="button" id="btn">Register</button>';
                } else {
                    // User is logged in, show Home button
                    echo '<button onclick="window.location.href=\'landingpage.php\';" type="button" id="btn">Home</button>';
                }
                ?>
            </div>
            <div class="col-md-3 genre-state-panel">
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
            <!-- State Panel -->
            <div class="col-md-3 genre-state-panel">
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
</body>
</html>
