<?php
session_start();
include 'dbconn.php'; // Ensure this file exists and contains your database connection code

// Example data (replace with dynamic data from your database)
$totalUsers = 150;
$totalFolktales = 74;
$activeUsers = 20;
$newUsers = 7;

$topFolktales = [
    "Siapa Lebih Bijak" => 150,
    "Gajah Yang Setia" => 50,
    "Seuncang Padi" => 20
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard</title>
    <style>
        /* General styling */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}

/* Sidebar styling */
.sidebar {
    background-color: #bcd6e7;
    width: 250px;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    display: flex;
    flex-direction: column;
    padding: 20px;
    transition: width 0.3s;
}

.sidebar.closed {
    width: 60px;
}

.sidebar h1 {
    font-size: 18px;
    color: #333;
    transition: opacity 0.3s;
}

.sidebar.closed h1 {
    opacity: 0;
}

.sidebar .toggle-btn {
    margin-bottom: 20px;
    border: none;
    background-color: transparent;
    font-size: 20px;
    cursor: pointer;
    color: #333;
}

.sidebar ul {
    list-style: none;
    padding: 0;
    margin: 20px 0;
}

.sidebar ul li {
    margin: 10px 0;
}

.sidebar ul li a {
    text-decoration: none;
    color: #333;
    font-size: 18px;
    display: flex;
    align-items: center;
}

.sidebar ul li a:hover {
    color: #0066cc;
}

/* Dashboard styling */
.dashboard {
    margin-left: 270px;
    padding: 20px;
    transition: margin-left 0.3s;
}

.sidebar.closed ~ .dashboard {
    margin-left: 80px;
}

.stats {
    display: flex;
    justify-content: space-between;
    gap: 20px;
}

.left {
    flex: 3;
}

.right {
    flex: 1;
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

/* Stat cards */
.stat-card {
    background-color: white;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.stat-card h3 {
    font-size: 18px;
    margin-bottom: 10px;
}

.progress-bar {
    height: 15px;
    background-color: #e0e0e0;
    border-radius: 5px;
    overflow: hidden;
    margin-top: 10px;
}

.progress-bar span {
    display: block;
    height: 100%;
    background-color: #007BFF;
}

/* Top folktales section */
.bar-chart {
    display: flex;
    justify-content: space-around;
    align-items: flex-end;
    height: 200px;
}

.bar {
    width: 20%;
    text-align: center;
    background-color: #fce8d5;
    border-radius: 5px;
    position: relative;
}

.bar:nth-child(2) {
    background-color: #daf4eb;
}

.bar:nth-child(3) {
    background-color: #edd4fc;
}

.bar span {
    position: absolute;
    bottom: -20px;
    width: 100%;
    font-size: 14px;
}

    </style>
    <link rel="stylesheet" href="dashboard.css">
    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('closed');
        }
    </script>
</head>
<body>
    <div class="sidebar">
        <h1>MalayanFolktalesHub</h1>
        <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
        <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">Generate Report</a></li>
        </ul>
        <a href="#" style="margin-top: auto; color: #333;">Log Out</a>
    </div>

    <div class="dashboard">
        <div class="stats">
            <div class="left">
                <div class="stat-card">
                    <h3>Total Users:</h3>
                    <div class="progress-bar">
                        <span style="width: <?= ($totalUsers / 200) * 100; ?>%;"></span>
                    </div>
                    <p><?= $totalUsers; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Total Folktales:</h3>
                    <div class="progress-bar">
                        <span style="width: <?= ($totalFolktales / 100) * 100; ?>%;"></span>
                    </div>
                    <p><?= $totalFolktales; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Active Users:</h3>
                    <div class="progress-bar">
                        <span style="width: <?= ($activeUsers / $totalUsers) * 100; ?>%;"></span>
                    </div>
                    <p><?= $activeUsers; ?></p>
                </div>
                <div class="stat-card">
                    <h3>New Users:</h3>
                    <div class="progress-bar">
                        <span style="width: <?= ($newUsers / $totalUsers) * 100; ?>%;"></span>
                    </div>
                    <p><?= $newUsers; ?></p>
                </div>
            </div>
            <div class="right">
                <div class="top-folktales">
                    <h3>Top 3 Most Viewed Folktales</h3>
                    <div class="bar-chart">
                        <?php foreach ($topFolktales as $title => $views): ?>
                            <div class="bar" style="height: <?= ($views / max($topFolktales)) * 100; ?>%;">
                                <span><?= $title; ?></span>
                                <span><?= $views; ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
