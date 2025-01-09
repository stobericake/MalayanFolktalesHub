<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Malayan Folktales Hub</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    </head>
    <body>
        <header class="header">
        <div class="navbar">
            <a href="#about" class="nav-link">About Us</a>
            <a href="#contact" class="nav-link">Contact</a>
            <a href="#faq" class="nav-link">FAQ</a>
        </div> </header>
        <style>
    
    body {
    font-family: 'Poppins', sans-serif;
    background: url('../assets/background.png') no-repeat center center/cover; /* Set the background image */
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
    overflow: hidden;
}
            
            /* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
        /* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}
}


/* Header */
.header {
    width: 100%;
    position: absolute;
    top: 0;
    display: flex;
    justify-content: flex-end;
    background-color: rgba(255, 255, 255, 0.5);
    padding: 10px 30px;
}

.navbar .nav-link {
    margin-left: 20px;
    text-decoration: none;
    color: #333;
    font-weight: 500;
    font-size: 14px;
    transition: color 0.3s;
}

.navbar .nav-link:hover {
    color: #32607C;
}

/* Main Section */
.main {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    height: 100%;
}

/* Welcome Section */
.welcome-section {
    text-align: center;
    background-color: rgba(255, 255, 255, 0.5); /* Increased transparency */
    padding: 100px 300px;
    border-radius: 20px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
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
    color: #666;
    margin-bottom: 30px;
}

.buttons {
    margin-top: 20px;
}

.btn {
    padding: 15px 30px; /* Larger button size */
    font-size: 16px;
    font-weight: 600;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin: 0 15px; /* Adjusted spacing between buttons */
    transition: background-color 0.3s, transform 0.2s;
}

.btn-signin {
    background-color: #32607C;
}

.btn-register {
    background-color: #68A0A8;
}

.btn-signin:hover {
    background-color: #234956;
    transform: scale(1.05);
}

.btn-register:hover {
    background-color: #4D8A91;
    transform: scale(1.05);
}


        </style>
  <main class="main">
        <div class="welcome-section">
            <h1>WELCOME TO</h1>
            <h2>Malayan Folktales Hub</h2>
            <div class="buttons">
                <button class="btn btn-signin" onclick="navigateToPage('login.php')">Sign In</button>
                <button class="btn btn-register" onclick="navigateToPage('register.php')">Register</button>
            </div>
        </div>
    </main>

    <script>
        function navigateToPage(page) {
            window.location.href = page;
        }
    </script>

    
    
</body>
</html>

    