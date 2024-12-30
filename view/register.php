<?php
session_start();

include 'dbconn.php';

// Handle registration
$errors = [];

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $password = mysqli_real_escape_string($conn, md5($_POST['password']));
    $confirm_password = mysqli_real_escape_string($conn, md5($_POST['confirmpassword']));

    // Check if email already exists
    $select = "SELECT * FROM user WHERE email = '$email'";
    $result = mysqli_query($conn, $select);

    if (mysqli_num_rows($result) > 0) {
        $errors[] = 'An account with this email already exists.';
    } else {
        if ($password != $confirm_password) {
            $errors[] = 'Passwords do not match!';
        } else {
            // Insert new user into the database
            $insert = "INSERT INTO user (email, name, password) 
                       VALUES ('$email', '$name', '$password')";
            if (mysqli_query($conn, $insert)) {
                header('Location: login.php');
                exit();
            } else {
                $errors[] = 'Error occurred while registering your account: ' . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up</title>
  <style>
    /* General reset and styling */
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background-color: #f5f5f5;
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
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }

    /* Image section styling */
    .image-section {
      flex: 1;
      background-color: #d4f0fc; /* Light blue */
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .image-section img {
      max-width: 90%;
      height: auto;
    }

    /* Form section styling */
    .form-section {
      flex: 1;
      background-color: #fff;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }

    .form-section h1 {
      font-size: 2rem;
      margin-bottom: 10px;
    }

    .form-section p {
      font-size: 1rem;
      color: #555;
      margin-bottom: 20px;
    }

    form {
      width: 100%;
      max-width: 400px;
    }

    .form-group {
      display: flex;
      align-items: center;
      margin-bottom: 15px;
      position: relative;
    }

    .form-group label {
      position: absolute;
      left: 10px;
      top: 50%;
      transform: translateY(-50%);
      color: #aaa;
    }

    .form-group input {
      width: 100%;
      padding: 10px 10px 10px 35px;
      font-size: 1rem;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .form-group input:focus {
      border-color: #66afe9;
      outline: none;
    }

    .submit-btn {
      width: 100%;
      padding: 10px;
      font-size: 1rem;
      background: linear-gradient(90deg, #56ccf2, #2f80ed);
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .submit-btn:hover {
      background: linear-gradient(90deg, #2f80ed, #56ccf2);
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="image-section">
      <img src="image.png" alt="Nature illustration">
    </div>
    <div class="form-section">
      <h1>WELCOME!</h1>
      <p>Register your account.</p>
      <form action="" method="POST">
        <div class="form-group">
          <label for="name"></label>
          <input type="text" id="name" name="name" placeholder="Name" required>
        </div>
        <div class="form-group">
          <label for="email"></label>
          <input type="email" id="email" name="email" placeholder="Email" required>
        </div>
        <div class="form-group">
          <label for="password"></label>
          <input type="password" id="password" name="password" placeholder="Password" required>
        </div>
        <div class="form-group">
          <label for="confirmpassword"></label>
          <input type="password" id="confirmpassword" name="confirmpassword" placeholder="Confirm Password" required>
        </div>
        <button type="submit" name="submit" class="submit-btn">Sign Up</button>
      </form>
    </div>
  </div>
</body>
</html>
