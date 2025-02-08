<?php
session_start();
include 'dbconn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $identifier = trim($_POST['identifier']); 
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);

    if (!empty($identifier) && !empty($password) && !empty($role)) {
        function validateUser ($table, $identifier, $password, $conn, $isId = false) {
            // Determine the ID column name based on the table
            $idColumn = $table . 'ID';

            if ($isId) {
                $query = "SELECT `$idColumn` AS id, `email`, `password` FROM `$table` WHERE `$idColumn` = ?";
            } else {
                $query = "SELECT `$idColumn` AS id, `email`, `password` FROM `$table` WHERE `email` = ?";
            }

            $stmt = $conn->prepare($query);
            $stmt->bind_param($isId ? 'i' : 's', $identifier);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if ($password == $row['password']) {
                    return $row; 
                }
            }
            return null;
        }

        $userData = null;
        switch ($role) {
            case 'user':
                $userData = validateUser ('user', $identifier, $password, $conn, false);
                break;
            case 'clerk':
                $userData = validateUser ('clerk', $identifier, $password, $conn, true); // ID for clerk
                break;
            case 'manager':
                $userData = validateUser ('manager', $identifier, $password, $conn, true); // ID for manager
                break;
        }

        if ($userData) {
            // Store user data in the session
            $_SESSION['userID'] = $userData['id']; 
            $_SESSION['email'] = $userData['email'];
            $_SESSION['role'] = $role;
            $_SESSION['loggedin'] = true;

            switch ($role) {
                case 'user':
                    header("Location: user/home.php");
                    break;
                case 'clerk':
                    header("Location: clerk/clerkdashboard.php");
                    break;
                case 'manager':
                    header("Location: manager/managerdashboard.php");
                    break;
            }
            exit;
        } else {
            $error = "Invalid ID/Email or password.";
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
            position: relative; 
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('../assets/background.png') no-repeat center center/cover;
            opacity: 0.5; 
            z-index: -1; 
        }

        .container {
            display: flex;
            flex-direction: row;
            width: 60%;
            height: 70%;
            background-color: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0 .2);
            position: relative;
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
            width: 100%;
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
            left: 10px;
            transform: translateY(-50%);
            font-size: 14px;
            color: #aaa;
        }

        .btn {
            width: 100px;
            margin-top: 10px;
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
            max-width: 80%;
            height: auto;
            border-radius: 15px;
        }

        .role-selection {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            width: 100%;
        }

        .role-selection label {
            font-size: 14px;
            color: #333333;
        }
    </style>
<script>
    function toggleIdentifierField() {
        const role = document.querySelector('input[name="role"]:checked').value;
        const identifierInput = document.getElementById('identifier');
        const registerLink = document.getElementById('register-link');

        identifierInput.placeholder = role === 'user' ? 'Email' : 'ID';

        if (role === 'user') {
            registerLink.style.display = 'inline';
        } else {
            registerLink.style.display = 'none';
        }
    }
</script>
</head>
<body>
    <div class="container">
        <div class="form-section">
            <h1>HELLO AGAIN!</h1>
            <p>Welcome back! We missed you!</p>
            <form action="" method="post">
                <div class="role-selection">
                    <label><input type="radio" name="role" value="user" required onclick="toggleIdentifierField()"> User</label>
                    <label><input type="radio" name="role" value="clerk" onclick="toggleIdentifierField()"> Clerk</label>
                    <label><input type="radio" name="role" value="manager" onclick="toggleIdentifierField()"> Manager</label>
                </div>
                <div class="input-group">
                    <i class="fa fa-user"></i>
                    <input type="text" id="identifier" name="identifier" placeholder="Email" required>
                </div>
                <div class="input-group">
                    <i class="fa fa-lock"></i>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
                <p id="register-link">Doesn't have an account? <a href="register.php">Register</a></p>
                <button type="submit" name="submit" class="btn">Sign In</button>
            </form>
            <?php if (!empty($error)): ?>
                <p style="color: red;"><?= $error ?></p>
            <?php endif; ?>
        </div>
        <div class="image-section">
            <img src="../assets/illustration.png?v=1" alt="Illustration">
        </div>
    </div>
</body>
</html>