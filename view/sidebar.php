 <!-- nak include sidebar, masukkan niii lepas <body>-->
 <!--<div class="d-flex">
    <?php // include 'sidebar.php'; ?>
        <div class="container-fluid p-4"> -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar {
            height: 100vh;
            background-color: #C3DFE6;
            color: black;
            padding: 15px;
            height: 100%;
        }
        .sidebar a {
            color: black;
            text-decoration: none;
            padding: 10px;
            display: block;
            border-radius: 5px;
        }
        .sidebar a:hover {
            background-color: #D4EBF8;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <h4 class="text-center"><img class="img" src="../assets/logo.png" alt="Logo"></h4>
            <h4 class="text-center"><? echo $_SESSION['role']?></h4>
            <hr>
            <?php
            if ($_SESSION['role'] === 'user') {
                echo '<a href="folktalelist.php"><i class="fa fa-book mr-3"></i>Folktales List</a>';
                echo '<a href="bookmark.php"><i class="fa fa-bookmark mr-3">Bookmarks</a>';
                echo '<a href="userprofile.php">Profile</a>';
                echo '<a href="logout.php">Logout</a>';
            } elseif ($_SESSION['role'] === 'clerk') {
                echo '<a href="clerkdashboard.php">Manage Services</a>';
                echo '<a href="reports.php">View Reports</a>';
                echo '<a href="logout.php">Logout</a>';
            } elseif ($_SESSION['role'] === 'manager') {
                echo '<a href="dashboard.php">Dashboard</a>';
                echo '<a href="managerdashboard.php">Manage Clerks</a>';
                echo '<a href="analytics.php"><i class="fa fa-chart-line mr-3"></i>Analytics</a>';
                echo '<a href="logout.php">Logout</a>';
            }
            ?>
        </div>
        
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.style.display = sidebar.style.display === 'none' ? 'block' : 'none';
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
