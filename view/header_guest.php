<?php
include 'dbconn.php';
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
    <header style="background-color: #C3DFE6; color: #0B2428;" class="p-3 shadow">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <!-- Logo and Text Container -->
                <a class="navbar-brand" href="#">
                    <h1 class="custom-title">MalayanFolktalesHub</h1>
                </a>
                <!-- Toggle Button for smaller screens -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!-- Navbar Links -->
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link btn-custom" href="home.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn-custom" href="../register.php">Register</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn-custom" href="../login.php">Login</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>