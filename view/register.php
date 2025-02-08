<?php
session_start();
include 'dbconn.php';
$errors = [];

if (isset($_POST['submit'])) {
    $userID = uniqid('USR'); 
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone_number = isset($_POST['phone_number']) ? mysqli_real_escape_string($conn, $_POST['phone_number']) : '';
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirmpassword']);

    // Check if email already exists
    $select = "SELECT * FROM user WHERE email = '$email'";
    $result = mysqli_query($conn, $select);

    if (mysqli_num_rows($result) > 0) {
        $errors[] = 'An account with this email already exists.';
    }

    if (empty($errors)) {
        $insert = "INSERT INTO user(email, phone_number, name, password) 
                   VALUES ('$email', '$phone_number', '$name', '$password')";
        if (mysqli_query($conn, $insert)) {
            header('Location: login.php');
            exit();
        } else {
            $errors[] = 'Error occurred while registering your account: ' . mysqli_error($conn);
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      position: relative;
      overflow: hidden;
    }

    body::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: #f5f5f5 url('../assets/background.png') no-repeat center center/cover;
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
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
      position: relative;
      z-index: 1;
    }

    .image-section {
      flex: 1;
      background-color: #d4f0fc;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .image-section img {
      max-width: 100%;
      height: auto;
    }

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
      margin-top: 0px;
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

    .validation-message {
      display: none;
      color: #ff6b6b;
      font-size: 0.875rem;
      margin-top: 5px;
      animation: shake 0.5s;
    }

    .validation-message i {
      margin-right: 5px;
    }

    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      25% { transform: translateX(-5px); }
      50% { transform: translateX(5px); }
      75% { transform: translateX(-5px); }
    }

    .valid {
      color: #4caf50;
    }

    .valid i {
      color: #4caf50;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="image-section">
      <img src="../assets/illustration.png?v=1" alt="Illustration">
    </div>
    <div class="form-section">
      <h1>WELCOME!</h1>
      <p>Register your account.</p>
      <?php if (!empty($errors)) : ?>
        <ul>
          <?php foreach ($errors as $error) : ?>
            <li style="color: red;"><?php echo $error; ?></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
      <form action="" method="POST" id="registrationForm">
        <div class="form-group">
          <input type="text" id="name" name="name" placeholder="Name" required>
          <div class="validation-message" id="nameValidation"><i class="fas fa-exclamation-circle"></i> Name can only contain letters and spaces.</div>
        </div>
        <div class="form-group">
          <input type="email" id="email" name="email" placeholder="Email" required>
          <div class="validation-message" id="emailValidation"><i class="fas fa-exclamation-circle"></i> Please enter a valid email.</div>
        </div>
        <div class="form-group">
          <input type="text" id="phone_number" name="phone_number" placeholder="Phone Number" required>
          <div class="validation-message" id="phoneValidation"><i class="fas fa-exclamation-circle"></i> Phone number must be 10 or 11 digits.</div>
        </div>
        <div class="form-group">
          <input type="password" id="password" name="password" placeholder="Password" required>
          <div class="validation-message" id="passwordValidation"><i class="fas fa-exclamation-circle"></i> Password must be at least 8 characters.</div>
        </div>
        <div class="form-group">
          <input type="password" id="confirmpassword" name="confirmpassword" placeholder="Confirm Password" required>
          <div class="validation-message" id="confirmPasswordValidation"><i class="fas fa-exclamation-circle"></i> Passwords do not match.</div>
        </div>
        <button type="submit" name="submit" class="submit-btn">Sign Up</button>
      </form>
    </div>
  </div>

  <script>
    document.getElementById('registrationForm').addEventListener('input', function(event) {
      const nameInput = document.getElementById('name');
      const emailInput = document.getElementById('email');
      const phoneInput = document.getElementById('phone_number');
      const passwordInput = document.getElementById('password');
      const confirmPasswordInput = document.getElementById('confirmpassword');

      const nameValidation = document.getElementById('nameValidation');
      const emailValidation = document.getElementById('emailValidation');
      const phoneValidation = document.getElementById('phoneValidation');
      const passwordValidation = document.getElementById('passwordValidation');
      const confirmPasswordValidation = document.getElementById('confirmPasswordValidation');

      if (event.target === nameInput) {
        if (!/^[a-zA-Z ]+$/.test(nameInput.value)) {
          nameValidation.style.display = 'block';
        } else {
          nameValidation.style.display = 'none';
        }
      }

      if (event.target === emailInput) {
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value)) {
          emailValidation.style.display = 'block';
        } else {
          emailValidation.style.display = 'none';
        }
      }

      if (event.target === phoneInput) {
        if (!/^\d{10,11}$/.test(phoneInput.value)) {
          phoneValidation.style.display = 'block';
        } else {
          phoneValidation.style.display = 'none';
        }
      }

      if (event.target === passwordInput) {
        if (passwordInput.value.length < 8) {
          passwordValidation.style.display = 'block';
        } else {
          passwordValidation.style.display = 'none';
        }
      }

      if (event.target === confirmPasswordInput) {
        if (confirmPasswordInput.value !== passwordInput.value) {
          confirmPasswordValidation.style.display = 'block';
        } else {
          confirmPasswordValidation.style.display = 'none';
        }
      }
    });
  </script>
</body>
</html>