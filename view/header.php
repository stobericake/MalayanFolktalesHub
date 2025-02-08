<?php
require_once 'session.php';
include 'dbconn.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        .custom-title {
            margin-left: 15px;
            font-family: 'Inter', sans-serif; 
            font-weight: 1000;
            font-size: 20px; 
            line-height: auto; 
            letter-spacing: 0; 
            background: linear-gradient(to right, #0B2428, #286A75);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent; 
        }
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #C3DFE6;
            color: #0B2428;
            z-index: 1000; 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
        }

        .btn-custom:hover {
            border-radius: 20px;
        }

        .btn-custom:hover {
            background-color:rgb(151, 183, 190);
            border-radius: 20px;
        }

        footer {
            background-color: #C3DFE6;
            color: #0B2428;
            text-align: center;
            position: fixed;
            bottom: 0;
            z-index: 1000; 
            width: 100%;
            box-shadow: 0 -4px 8px rgba(0, 0, 0, 0.1); 
            display: flex;
            justify-content: center; 
            align-items: center; 
            height: 50px;
        }

        .footer-title{
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <header style="background-color: #C3DFE6; color: #0B2428;" class="p-3 shadow">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    <h1 class="custom-title">MalayanFolktalesHub</h1>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav">
                        <?php
                        if (isset($_SESSION['role'])) {
                            $role = getSession('role');
                            $userID = isset($_SESSION['userID']) ? $_SESSION['userID'] : null;
                            if ($role === 'user') {
                                echo '<li class="nav-item"><a class="nav-link btn-custom" href="home.php">Home</a></li>';
                                echo '<li class="nav-item"><a class="nav-link btn-custom" href="bookmark.php">Bookmarks</a></li>';
                                echo '<li class="nav-item"><a class="nav-link btn-custom" href="userprofile.php">Profile</a></li>';
                                echo '<li class="nav-item"><a class="nav-link btn-custom" href="../logout.php">Logout</a></li>';
                            } elseif ($role === 'clerk') {
                                echo '<li class="nav-item"><a class="nav-link btn-custom" href="clerkdashboard.php">Home</a></li>';
                                echo '<li class="nav-item"><a class="nav-link btn-custom" href="clerklisting.php">Manage Folktales</a></li>';
                                echo '<li class="nav-item"><a class="nav-link btn-custom" href="../logout.php">Logout</a></li>';
                            } elseif ($role === 'manager') {
                                echo '<li class="nav-item"><a class="nav-link btn-custom" href="managerdashboard.php">Home</a></li>';
                                echo '<li class="nav-item"><a class="nav-link btn-custom" href="folktalelist.php">View Collections</a></li>';
                                echo '<li class="nav-item"><a class="nav-link btn-custom" href="../logout.php">Logout</a></li>';
                            }
                        } else {
                            echo '<li class="nav-item"><a class="nav-link btn-custom" href="home.php">Home</a></li>';
                            echo '<li class="nav-item"><a class="nav-link btn-custom" href="register.php">Register</a></li>';
                            echo '<li class="nav-item"><a class="nav-link btn-custom" href="login.php">Login</a></li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <footer>
        <p class="footer-title">&copy; <?php echo date("Y"); ?> MalayanFolktalesHub. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>