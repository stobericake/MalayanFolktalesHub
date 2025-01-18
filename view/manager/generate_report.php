<?php
session_start();
include '../dbconn.php';

// Check if the user is an admin
if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'manager') {
    die("Unauthorized access.");
}

// Fetch data for the dashboard
$totalOriginResult = $conn->query("SELECT COUNT(DISTINCT state) AS total_origin FROM folktales");
$totalOriginRow = $totalOriginResult->fetch_assoc();
$totalOrigins = $totalOriginRow['total_origin'];

$totalUserResult = $conn->query("SELECT COUNT(*) as total_user FROM user");
$totalUserRow = $totalUserResult->fetch_assoc();
$totalUser = $totalUserRow['total_user'];

$totalGenreResult = $conn->query("SELECT COUNT(DISTINCT genre) AS genre_count FROM folktales");
$totalGenreRow = $totalGenreResult->fetch_assoc();
$totalGenre = $totalGenreRow['genre_count'];

$query = "SELECT f.title, bf.count FROM bookmark_folktale bf JOIN folktales f ON bf.folktalesID = f.folktaleID ORDER BY bf.count DESC LIMIT 5";
$result = mysqli_query($conn, $query);

$folktaleTitles = [];
$bookmarkCounts = [];

while ($row = mysqli_fetch_assoc($result)) {
    $folktaleTitles[] = $row['title'];
    $bookmarkCounts[] = $row['count'];
}

// Handle PDF generation
if (isset($_GET['generate_pdf'])) {
    require('../../fpdf/fpdf.php'); // Include FPDF library

    // Fetch data for the PDF report
    $folktalesOriginQuery = "SELECT DISTINCT state FROM folktales";
    $folktalesOriginResult = $conn->query($folktalesOriginQuery);

    $folktalesGenreQuery = "SELECT DISTINCT genre FROM folktales";
    $folktalesGenreResult = $conn->query($folktalesGenreQuery);

    $bookmarksQuery = "SELECT f.title, bf.count FROM bookmark_folktale bf JOIN folktales f ON bf.folktalesID = f.folktaleID ORDER BY bf.count DESC";
    $bookmarksResult = $conn->query($bookmarksQuery);

    $usersQuery = "SELECT userID, name, email FROM user";
    $usersResult = $conn->query($usersQuery);

    // Create a new PDF instance
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);

    // Add a title
    $pdf->Cell(0, 10, 'Folktales Report', 0, 1, 'C');
    $pdf->Ln(10);

    // Folktales Origin
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'Folktales Origin', 0, 1);
    $pdf->SetFont('Arial', '', 12);
    while ($row = $folktalesOriginResult->fetch_assoc()) {
        $pdf->Cell(0, 10, $row['state'], 0, 1);
    }
    $pdf->Ln(10);

    // Folktales Genre
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'Folktales Genre', 0, 1);
    $pdf->SetFont('Arial', '', 12);
    while ($row = $folktalesGenreResult->fetch_assoc()) {
        $pdf->Cell(0, 10, $row['genre'], 0, 1);
    }
    $pdf->Ln(10);

    // Bookmarks
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'Top Bookmarked Folktales', 0, 1);
    $pdf->SetFont('Arial', '', 12);
    while ($row = $bookmarksResult->fetch_assoc()) {
        $pdf->Cell(0, 10, $row['title'] . ' - ' . $row['count'] . ' bookmarks', 0, 1);
    }
    $pdf->Ln(10);

    // Users
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'User List', 0, 1);
    $pdf->SetFont('Arial', '', 12);
    while ($row = $usersResult->fetch_assoc()) {
        $pdf->Cell(0, 10, 'ID: ' . $row['userID'] . ' - Name: ' . $row['name'] . ' - Email: ' . $row['email'], 0, 1);
    }

    // Output the PDF for download
    $pdf->Output('D', 'Folktales_Report.pdf');
    exit;
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
            background: url('../assets/background.png') no-repeat center center/cover;
            background-size: cover;
        }
        .content {
            padding-top: 100px;
            margin-left: 30px;
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

        canvas {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .btn-lg {
            padding: 15px 30px;
            font-size: 18px;
            border-radius: 10px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .btn-lg:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<?php include '../header.php'; ?>
<div class="content">
    <div class="container">
        <!-- Card Section -->
        <div class="card-section">
            <!-- Card 1: User Report -->
            <a href="../user_report.php" class="card">
                <i class="bi bi-person-heart icon-pic"></i>
                <h3>USER REPORT</h3>
                <p class="total">Total: <span id="genre-total"><?php echo $totalUser; ?></span></p>
            </a>

            <!-- Card 2: Folktales Origin -->
            <a href="../origin_report.php" class="card">
                <i class="bi bi-geo-fill icon-pic"></i>
                <h3>FOLKTALES ORIGIN</h3>
                <p class="total">Total: <span id="genre-total"><?php echo $totalOrigins; ?></span></p>
            </a>

            <!-- Card 3: Folktales Genre -->
            <a href="../genre_report.php" class="card">
                <i class="bi bi-journal icon-pic"></i>
                <h3>FOLKTALES GENRE</h3>
                <p class="total">Total: <span id="genre-total"><?php echo $totalGenre; ?></span></p>
            </a>
        </div>

        <!-- Generate Report Button -->
        <div class="text-center mt-4">
            <a href="?generate_pdf=1" class="btn btn-lg">
                <i class="bi bi-download"></i> Generate Report
            </a>
        </div>

        <!-- Chart Section -->
        <div class="chart-section">
            <canvas id="salesChart" width="1300" height="400"></canvas>
        </div>
    </div>
</div>

<script>
    // Pass PHP data to JavaScript
    const folktaleTitles = <?php echo json_encode($folktaleTitles); ?>;
    const bookmarkCounts = <?php echo json_encode($bookmarkCounts); ?>;

    // Render Chart
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: folktaleTitles,
            datasets: [{
                label: 'Bookmarks',
                data: bookmarkCounts,
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
                        text: 'Bookmark Count'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Folktales'
                    }
                }
            }
        }
    });
</script>
</body>
</html>