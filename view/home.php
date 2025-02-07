<?php
include 'dbconn.php';


$userID = isset($_SESSION['userID']) ? $_SESSION['userID'] : null;
// Fetch unique genres
$genres_query = "SELECT DISTINCT LOWER(TRIM(genre)) AS genre FROM folktales";
$genres_result = mysqli_query($conn, $genres_query);

// Fetch unique states
$states_query = "SELECT DISTINCT LOWER(TRIM(state)) AS state FROM folktales";
$states_result = mysqli_query($conn, $states_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Fredoka+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            color: #fff;
            margin: 0;
            padding: 0;
            scroll-behavior: smooth; /* Smooth scrolling */
        }

        /* Main Section - Full Page */
        #main-section {
            background-image: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('../assets/background.png');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            height: 100vh; /* Full viewport height */
            min-height: 600px; /* Minimum height for smaller screens */
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            padding-top: 120px;
        }

        .content {
            margin-left: 50px;
        }

        .content h3 {
            font-family: 'Fredoka One', cursive;
            font-size: 50px;
            color:rgb(25, 159, 255);
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            animation: fadeInDown 1s ease-in-out;
        }

        .content p {
            font-size: 18px;
            color: #C3DFE6;
            animation: fadeInUp 1s ease-in-out;
        }

        #btn {
            width: 200px;
            height: 40px;
            margin-top: 10px;
            background:rgb(130, 129, 130);
            border: none;
            border-radius: 25px;
            color: #fff;
            font-weight: 600;
            transition: transform 0.3s ease, background 0.3s ease;
        }

        #btn:hover {
            background:rgb(152, 119, 117);
            transform: scale(1.1);
        }

        .genre-state-panel {
            background-color: rgb(68, 116, 156);
            padding: 20px;
            border-radius: 15px;
            height: 400px;
            overflow-y: auto;
            margin-right: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            animation: fadeInRight 1s ease-in-out;
        }

        .genre-state-panel h5 {
            font-family: 'Fredoka One', cursive;
            font-size: 24px;
            color:rgb(228, 232, 235);
            margin-bottom: 20px;
        }

        .genre-state-panel button {
            background-color:rgb(255, 236, 131);
            border: none;
            border-radius: 10px;
            padding: 10px;
            text-align: center;
            font-weight: 600;
            color: #32607C;
            transition: transform 0.3s ease, background 0.3s ease;
            width: 100%;
            margin-bottom: 10px;
        }

        .genre-state-panel button:hover {
            background-color: #FFA500;
            transform: scale(1.05);
        }

                /* Modal Styling */
                .modal-content {
            border-radius: 15px;
            background-color: #fff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            border-bottom: none;
            background-color: #32607C;
            color: #fff;
            border-radius: 15px 15px 0 0;
        }

        .modal-title {
            font-family: 'Fredoka One', cursive;
            font-size: 24px;
        }

        .modal-body {
            max-height: 450px;
            overflow-y: auto;
            padding: 20px;
        }

        .modal-body ul {
            list-style-type: none;
            padding: 0;
        }

        .modal-body li {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            color: #32607C;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background 0.3s ease;
        }

        .modal-body li:hover {
            background-color: #f0f0f0;
        }

        .modal-body li:last-child {
            border-bottom: none;
        }

        .view-details-btn {
            background-color: #32607C;
            color: white;
            border: none;
            border-radius: 20px;
            padding: 5px 10px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .view-details-btn:hover {
            background-color: #234956;
        }

        /* Top Folktales Section */
        #top-folktales-section {
            background-image: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('../assets/bg_2.png');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            height: 100vh; 
            min-height: 500px;
            align-items: center;
            justify-content: center;
            margin: auto;
            display: flex;
        }

        .container h2 {
            font-family: 'Fredoka One', cursive;
            font-size: 50px;
            color:rgb(255, 236, 131);
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }


        .btn-primary {
            background-color: #32607C;
            border: none;
            border-radius: 25px;
            padding: 8px 20px;
            font-size: 14px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #234956;
            transform: scale(1.05);
        }

        /* Animations */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
</head>
<body class="font-sans text-gray-700">
    <?php if ($userID): ?>
        <?php include 'header.php'; ?>
    <?php else: ?>
        <?php include 'header_guest.php'; ?>
    <?php endif; ?>

    <!-- Main Section - Full Page -->
    <section id="main-section">
        <div class="content">
            <div class="row">
                <div class="col-md-5">
                    <h3>Welcome to <br> MalayanFolktalesHub</h3>
                    <p>"Preserving the past, inspiring the future through the tales of our ancestors."</p>
                    <button onclick="window.location.href='user/folktalelist.php';" type="button" id="btn">View all collections</button>
                </div>
                <!-- Genre Panel -->
                <div class="col-md-3 genre-state-panel">
                    <h5>FOLKTALE GENRE</h5>
                    <?php
                    if ($genres_result && mysqli_num_rows($genres_result) > 0) {
                        $unique_genres = [];
                        while ($row = mysqli_fetch_assoc($genres_result)) {
                            $genre = ucfirst($row['genre']); // Capitalize the first letter for display
                            if (!in_array($genre, $unique_genres)) {
                                $unique_genres[] = $genre;
                                echo "<button data-bs-toggle='modal' data-bs-target='#titlesModal' onclick=\"fetchTitles('genre', '" . htmlspecialchars($row['genre']) . "')\">" 
                                    . htmlspecialchars($genre) . 
                                    "</button>";
                            }
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
                        $unique_states = [];
                        while ($row = mysqli_fetch_assoc($states_result)) {
                            $state = ucfirst($row['state']); // Capitalize the first letter for display
                            if (!in_array($state, $unique_states)) {
                                $unique_states[] = $state;
                                echo "<button data-bs-toggle='modal' data-bs-target='#titlesModal' onclick=\"fetchTitles('state', '" . htmlspecialchars($row['state']) . "')\">" 
                                    . htmlspecialchars($state) . 
                                    "</button>";
                            }
                        }
                    } else {
                        echo "<p>No states available.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>

<!-- Top 3 Folktales Section -->
<section id="top-folktales-section" class="py-5">
    <div class="container">
        <h2 class="text-center mb-4"><i class="bi bi-stars text-warning"></i> Top 3 Most Bookmarked Folktales <i class="bi bi-stars text-warning"></i></h2>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php
            // Fetch top 3 folktales with highest bookmarks
            $top_folktales_query = "SELECT f.folktaleID, f.title, f.genre, f.state, bf.count
                FROM folktales f
                LEFT JOIN bookmark_folktale bf ON f.folktaleID = bf.folktaleID
                GROUP BY f.folktaleID
                ORDER BY bf.count DESC
                LIMIT 3;
            ";
            $top_folktales_result = mysqli_query($conn, $top_folktales_query);

            if ($top_folktales_result && mysqli_num_rows($top_folktales_result) > 0) {
                $rank = 1; // Initialize rank counter
                while ($row = mysqli_fetch_assoc($top_folktales_result)) {
                    // Assign medal emoji based on rank
                    $medal_emoji = '';
                    if ($rank == 1) {
                        $medal_emoji = '🥇'; // Gold medal for 1st place
                    } elseif ($rank == 2) {
                        $medal_emoji = '🥈'; // Silver medal for 2nd place
                    } elseif ($rank == 3) {
                        $medal_emoji = '🥉'; // Bronze medal for 3rd place
                    }

                    echo '
                    <div class="col">
                        <div class="card h-100 shadow-sm animate__animated animate__fadeInUp border-0" style="border-radius: 20px; background: linear-gradient(145deg, #f9f9f9, #fff5f5);">
                            <div class="card-body text-center p-4">
                                <div class="icon-container mb-3" style="font-size: 2.5rem;">
                                    ' . $medal_emoji . '
                                </div>
                                <h5 class="card-title fw-bold" style="color:rgb(137, 184, 255);">' . htmlspecialchars($row['title']) . '</h5>
                                <p class="card-text" style="color: #6c757d;">
                                    <strong><i class="bi bi-music-note-beamed"></i> Genre:</strong> ' . htmlspecialchars($row['genre']) . '<br>
                                    <strong><i class="bi bi-geo-alt"></i> State:</strong> ' . htmlspecialchars($row['state']) . '<br>
                                    <strong><i class="bi bi-bookmark-check"></i> Bookmarks:</strong> ' . $row['count'] . '
                                </p>
                                <a href="user/folktaledetails.php?id=' . $row['folktaleID'] . '" class="btn view-details-btn" style="background-color:rgb(85, 139, 255); color: white; border-radius: 20px; padding: 8px 20px;">
                                    <i class="bi bi-eye"></i> View Details
                                </a>
                            </div>
                        </div>
                    </div>';
                    $rank++; // Increment rank counter
                }
            } else {
                echo '<div class="col"><p class="text-center">No folktales found.</p></div>';
            }
            ?>
        </div>
    </div>
</section>

    <!-- Modal for Titles -->
    <div class="modal fade" id="titlesModal" tabindex="-1" aria-labelledby="titlesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="titlesModalLabel">Folktale Titles</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul id="titlesList">
                        <!-- Titles will be dynamically inserted here -->
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function fetchTitles(type, value) {
            fetch(`fetch_titles.php?type=${type}&value=${encodeURIComponent(value)}`)
                .then(response => response.json())
                .then(data => {
                    const titlesList = document.getElementById('titlesList');
                    titlesList.innerHTML = ''; // Clear previous content
                    if (data.length > 0) {
                        data.forEach(item => {
                            const li = document.createElement('li');
                            li.innerHTML = `
                                <span>${item.title}</span>
                                <button class="view-details-btn" onclick="window.location.href='user/folktaledetails.php?id=${item.folktaleID}'"> <i class="bi bi-eye"></i> View Details</button>
                            `;
                            titlesList.appendChild(li);
                        });
                    } else {
                        titlesList.innerHTML = '<li>No titles found.</li>';
                    }
                })
                .catch(error => console.error('Error fetching titles:', error));
        }
    </script>
</body>
</html>