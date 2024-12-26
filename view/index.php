<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Placeholder authentication logic
    if ($username === 'admin' && $password === 'password') {
        echo 'Login successful!';
    } else {
        echo 'Invalid username or password.';
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">


    <style>
    body {
        margin: 0;
        padding: 0;
        font-family: 'Poppins', sans-serif;
        background-color: #f5f5f5;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .container {
        display: flex;
        width: 60%;
        height: 70%;
        background-color: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .form-section {
        flex: 1;
        padding: 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background-color: #ffffff;
    }

    .form-section h1 {
        margin: 0;
        font-size: 32px;
        color: #333333;
    }

    .form-section p {
        margin-top: 10px;
        color: #666666;
        font-size: 14px;
    }

    .input-group {
        width: 80%;
        margin-top: 20px;
        position: relative;
    }

    .input-group label {
        display: block;
        font-size: 14px;
        color: #333333;
        margin-bottom: 5px;
    }

    .input-group input {
        width: 100%;
        padding: 10px 5px 10px 45px;
        border: 1px solid #ddd;
        border-radius: 10px;
        background-color: #F0EDFF;
        font-size: 14px;
    }

    .input-group i {
        position: absolute;
        top: 50%;
        left: 10px; /* Icon position inside the input */
        transform: translateY(-50%);
        font-size: 14px;
        color: #aaa;
    }

    .btn {
        margin-top: 20px;
        width: 100px;
        padding: 12px;
        justify-content: center;
        align-items: center;
        background-color: #32607C;
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 12px;
        font-weight: bold;
        cursor: pointer;
        display: flex;
    }

    .image-section {
        flex: 1;
        background-color: #C3DFE6;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .image-section img {
        max-width: 100%;
        height: auto;
        border-radius: 15px;
    }
    </style>

</head>
<body>
    <div class="container">
        <div class="form-section">
            <h1>HELLO AGAIN!</h1>
            <p>Welcome back! We missed you!</p>
            <form action="login.php" method="post">
                <div class="input-group">
                       <i class="fa fa-user"></i>
                    <input type="text" id="username" name="username" placeholder="Username" required>
                </div>
                <div class="input-group">
                <i class="fa-solid fa-lock"></i>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
            </form> 
            <button type="submit" class="btn">Sign In</button>
        </div>
        <div class="image-section">
            <img src="assets\illustration.png" alt="Illustration">
        </div>
    </div>
</body>
</html>
