<?php
// Simulating user roles for demonstration purposes
// Replace this with actual authentication logic
$user_role = 'normal_user'; // Change this to 'normal_user', 'manager', or 'clerk' to test.
?>

<!doctype html>
<html lang="en">
  <head>
    <title>Sidebar with Role-Based Content</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>

          * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color:rgb(255, 255, 255);
            margin: 0;
        }

        #sidebar {
            width: 300px;
            height: 100vh;
            background-color: #C3DFE6;
            color: black;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
            padding: 20px;
        }
      

        #sidebar .img {
            display: block;
            width: 80%;
            margin: 20px auto;
        }

        #sidebar p {
            text-align: center;
            margin-top: 10px;
            font-weight: bold;
        }

        #sidebar ul {
            list-style: none;
            padding: 0;
        }

        #sidebar ul li {
            margin: 15px 0;
        }

        #sidebar ul li a {
            color: black;
            text-decoration: none;
            font-size: 16px;
            display: block;
            margin: 5px 0;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        #sidebar ul li a:hover {
            background-color: #D4EBF8;
            color: #000;
        }

        #sidebar .logout {
            text-align: center;
            margin-top: 20px;
        }

        #sidebar .logout a {
            color: white;
            font-size: 16px;
            text-decoration: none;
        }

        #content {
            margin-left: 320px;
            padding: 20px;
        }

        @media (max-width: 768px) {
            #sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            #content {
                margin-left: 0;
            }

            #sidebar ul li.active a {
             background-color: #D4EBF8; 
            color: #000; 
            transform: translateY(-3px); 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

        }
    </style>
  </head>
  <body>
    <div class="wrapper d-flex align-items-stretch">
      <nav id="sidebar">
        <img class="img" src="../assets/logo.png" alt="Logo">
        <p>
          <?php 
          if ($user_role === 'normal_user') echo 'Reader';
          elseif ($user_role === 'manager') echo 'Manager';
          elseif ($user_role === 'clerk') echo 'Clerk'; 
          ?>
        </p>

        <ul class="list-unstyled components mb-5">
          <li class="active">
            <a href="userhomedashboard.php"><i class="fa fa-home mr-3"></i> Home</a>
          </li>
          <?php if ($user_role === 'normal_user'): ?>
            <li><a href="folktalelist.php"><i class="fa fa-book mr-3"></i> Folktales List</a></li>
            <li><a href="bookmark.php"><i class="fa fa-bookmark mr-3"></i> Bookmarks</a></li>
          <?php elseif ($user_role === 'manager'): ?>
            <li><a href="generatereport.php"><i class="fa fa-chart-line mr-3"></i> Generate Report</a></li>
          <?php elseif ($user_role === 'clerk'): ?>
            <li><a href="clerkdashboard.php"><i class="fa fa-cog mr-3"></i> Folktales Management</a></li>
          <?php endif; ?>
        </ul>

        <div class="logout">
          <a href="#"><i class="fa fa-sign-out mr-3"></i> Sign Out</a>
        </div>
      </nav>


  </body>
</html>