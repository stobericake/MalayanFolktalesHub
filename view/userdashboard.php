<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Malayan Folktales Hub</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: url('../assets/background.png') no-repeat center center/cover;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            overflow: hidden;
        }

        /* Header */
        .header {
            width: 100%;
            position: absolute;
            top: 0;
            display: flex;
            justify-content: flex-end;
            background-color: rgba(255, 255, 255, 0.5);
            padding: 10px 20px;
        }

        .navbar .nav-link {
            margin-left: 15px;
            text-decoration: none;
            color: #333;
            font-weight: 500;
            font-size: 14px;
            transition: color 0.3s;
        }

        .navbar .nav-link:hover {
            color: #32607C;
        }

        /* Welcome Section */
        .welcome-section {
            text-align: center;
            background-color: rgba(255, 255, 255, 0.5);
            padding: 100px 40px;
            border-radius: 20px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            max-width: 100%;
        }

        .welcome-section h1 {
            font-size: 200px; /* Larger size */
            font-weight: bold; /* Bolded text */
            color: #444;
            margin-bottom: 10px;
        }

        .welcome-section h2 {
            font-size: 100px; /* Larger size */
            font-weight: bold; /* Bolded text */
            color: #666;
            margin-bottom: 20px;
        }

        .buttons {
            margin-top: 20px;
            display: flex; /* Make the buttons inline */
            justify-content: center; /* Center the buttons horizontally */
            gap: 15px; /* Add spacing between buttons */
        }

        .btn {
            padding: 20px 30px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        .btn-guest {
            padding: 15px;
            margin: 10px;
            font-weight: 600;
            color: black;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.2s;
        }

        .btn-guest:hover {
            background-color:rgba(219, 234, 239, 0.51);
        }

        .btn-signin {
            background-color: #32607C;
        }

        .btn-register {
            background-color: #68A0A8;
        }

        .btn-signin:hover,
        .btn-register:hover {
            transform: scale(1.05);
        }

        .btn-signin:hover {
            background-color: #234956;
        }

        .btn-register:hover {
            background-color: #4D8A91;
        }

        /* Responsive Design */
        @media (min-width: 768px) {
            .welcome-section {
                padding: 100px 80px;
            }

            .welcome-section h1 {
                font-size: 60px;
            }

            .welcome-section h2 {
                font-size: 30px;
            }

            .btn {
                padding: 20px 40px;
                font-size: 15px;
            }
        }

        @media (min-width: 1024px) {
            .welcome-section {
                padding: 150px 300px;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="navbar">
            <a href="#about" class="nav-link">About Us</a>
            <a href="#contact" class="nav-link">Contact</a>
            <a href="#faq" class="nav-link">FAQ</a>
        </div>
    </header>

    <main class="main">
        <div class="welcome-section">
            <h1>WELCOME TO</h1>
            <h2>Malayan Folktales Hub</h2>
            <div class="buttons">
                <button class="btn btn-signin" onclick="navigateToPage('login.php')">Login</button>
                <button class="btn btn-register" onclick="navigateToPage('register.php')">Register</button>
            </div>
            <button class="btn-guest" onclick="navigateToPage('home.php')">Continue as Guest?</button>
        </div>
    </main>

    <script>
        function navigateToPage(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>
