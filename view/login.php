<?php
session_start();
include 'dbconn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        // Function to validate user from a table
        function validateUser($table, $email, $password, $conn) {
            // Determine the ID column name based on the table
            $idColumn = $table . 'id'; // e.g., 'userid', 'clerkid', 'managerid'

            // Use prepared statements to prevent SQL injection
            $query = "SELECT `$idColumn` AS id, `email`, `password` FROM `$table` WHERE `email` = '$email'";
            $result = mysqli_query($conn, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                // Direct password comparison
                if ($password == $row['password']) {
                    return $row; // Return the row data if valid
                }
            }
            return null; // Return null if invalid
        }

        // Check all tables
        $role = '';
        $userData = validateUser('user', $email, $password, $conn);
        if ($userData) {
            $role = 'user';
        } else {
            $userData = validateUser('clerk', $email, $password, $conn);
            if ($userData) {
                $role = 'clerk';
            } else {
                $userData = validateUser('manager', $email, $password, $conn);
                if ($userData) {
                    $role = 'manager';
                }
            }
        }

        if ($role) {
            // Store user data in the session
            $_SESSION['userID'] = $userData['id']; // Use the dynamically selected ID column
            $_SESSION['email'] = $userData['email'];
            $_SESSION['role'] = $role;
            $_SESSION['loggedin'] = true;

            // Redirect based on role
            switch ($role) {
                case 'user':
                    header("Location: home.php");
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
            $error = "Invalid Email or password.";
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
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden; /* Prevent scrollbars */
        }


        .container {
            display: flex;
            flex-direction: row;
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
            max-width: 80%;
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
            <form action="" method="post">
                <div class="input-group">
                    <i class="fa fa-user"></i>
                    <input type="text" id="email" name="email" placeholder="Email" required>
                </div>
                <div class="input-group">
                    <i class="fa fa-lock"></i>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
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