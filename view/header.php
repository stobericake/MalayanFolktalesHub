<?php
// header.php
session_start();
// Database connection
include '../dbconn.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        .custom-title {
            margin-left: 15px;
            font-family: 'Inter', sans-serif; /* Use 'Inter' font */
            font-weight: 1000;
            font-size: 20px; /* Font size */
            line-height: auto; /* Adjusted line height */
            letter-spacing: 0; /* No letter spacing */
            background: linear-gradient(to right, #0B2428, #286A75); /* Placeholder for gradient */
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent; /* Gradient effect */
        }
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #C3DFE6;
            color: #0B2428;
            z-index: 1000; /* Ensure it stays above other content */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Optional: Adds a shadow for better visibility */
        }

        .btn-custom:hover {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* More prominent shadow */
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <header style="background-color: #C3DFE6; color: #0B2428;" class="p-3 shadow d-flex justify-content-between align-items-center">
        <!-- Logo and Text Container -->
        <div class="d-flex align-items-center gap-3">
            <h1 class="custom-title">MalayanFolktalesHub</h1>
        </div>
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <?php
        // Check if the user is logged in based on session
        if (isset($_SESSION['role'])) {
            $role = $_SESSION['role'];
            if ($role === 'user') {
                echo '<a class="btn btn-custom" href="landingpage.php">Home</a>';
                echo '<a class="btn btn-custom" href="bookmark.php">Bookmarks</a>';
                echo '<a class="btn btn-custom" href="userprofile.php">Profile</a>';
                echo '<a class="btn btn-custom" href="logout.php">Logout</a>';
            } elseif ($role === 'clerk') {
                echo '<a class="btn btn-custom" href="managerclerkdashboard.php">Home</a>';
                echo '<a class="btn btn-custom" href="clerklisting.php">Manage Folktales</a>';
                echo '<a class="btn btn-custom" href="logout.php">Logout</a>';
            } elseif ($role === 'manager') {
                echo '<a class="btn btn-custom" href="managerclerkdashboard.php">Home</a>';
                echo '<a class="btn btn-custom" href="report.php">Report</a>';
                echo '<a class="btn btn-custom" href="logout.php">Logout</a>';
            }
        } else {
            // If the user is not logged in, show Register and Login
            echo '<a class="btn btn-custom" href="landingpage.php">Home</a>';
            echo '<a class="btn btn-custom" href="register.php">Register</a>';
            echo '<a class="btn btn-custom" href="login.php">Login</a>';
        }
        ?>
        </div>
    </header>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
