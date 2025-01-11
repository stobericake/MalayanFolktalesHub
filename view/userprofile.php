<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Card with Sidebar</title>
    <style>
        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F8F9FD;
            display: flex;
        }

        /* Sidebar Styles */
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

        /* Content Area */
        #content {
            margin-left: 320px;
            padding: 20px;
            width: calc(100% - 320px);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background:#F8F9FD;
        }

        /* Profile Card Styles */
        .profile-card {
            background: #F8F9FD;
            border-radius: 30px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            text-align: center;
            width: 1000px;
            padding: 100px;
            position: relative;
            
        }

        .profile-card .avatar {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            object-fit: cover;
            border: 6px solid #fff;
            margin-top: -80px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .profile-card h2 {
            font-size: 32px;
            margin: 15px 0 8px;
        }

        /* Profile Card Styles */
    .profile-card {
    background: #C3DFE6;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    text-align: center;
    width: 1000px;
    padding: 100px;
    position: relative;
}


        .profile-card .info {
            display: flex;
            justify-content: space-between;
            margin: 30px 0;
            font-size: 16px;
            font-weight: bold;
            color: #444;
        }

        .profile-card .info div {
            text-align: center;
        }

        .profile-card .btn {
    display: inline-block;
    margin-top: 30px;
    background: #FFF5E0; 
    color: black; 
    padding: 15px 30px;
    border-radius: 30px;
    text-decoration: none;
    font-size: 16px;
    font-weight: bold;
    border: none;
    transition: background 0.3s ease;
}

.profile-card .btn:hover {
    background: #FFF5E0; 
}

    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav id="sidebar">
        <img class="img" src="../assets/logo.png" alt="Logo">
        <p>
          <?php 
          // Simulating user roles for demonstration purposes
          $user_role = 'normal_user'; // Change to 'normal_user', 'manager', or 'clerk' to test
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

   <!-- Content Area -->
<div id="content">
    <div class="profile-card">
        <img src="https://via.placeholder.com/140" alt="Avatar" class="avatar">
        <h2>Samantha Jones</h2>
        <p>New York, United States</p>
        <p>Web Producer - Web Specialist<br>Columbia University - New York</p>
        <!-- Update and Delete Buttons -->
        <div class="actions">
            <button class="btn update-btn">Update Pofile</button>
            <button class="btn delete-btn">Delete Profile </button>
        </div>
    </div>
</div>

</body>
</html>
