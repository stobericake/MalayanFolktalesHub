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
       #content {
        margin-left: 200px; /* Push content to the right of the sidebar */
        padding: 10px; /* Add some padding to the content */
        width: calc(100% - 250px); /* Adjust width to fit beside the sidebar */
      }
      #sidebar .img {
        margin-top: 20px; /* Gap from top */
        margin-left: 20px; /* Align logo */
        display: block;
        width: 80%; 
      }

      #sidebar .p {
        margin-top: 0px; /* Gap from top */
        margin-left: 20px; /* Align logo */
        margin-bottom: 30px;
        color: black;
      }


      #sidebar .logout {
        margin-bottom: 0px; /* Push to bottom */
        text-align: center;
        padding: 10px;
      }
    </style>
  </head>
  <body>
    <div class="wrapper d-flex align-items-stretch">
      <nav id="sidebar">
      <div class="custom-menu">
					<button type="button" id="sidebarCollapse" class="btn btn-primary">
	        </button>
        </div>
        <img class="img" src="../images/logo.png" alt="Logo">
        <?php if ($user_role === 'normal_user'): ?>
            <p class="p">Reader</p>
          <?php elseif ($user_role === 'manager'): ?>
            <p class="p">Manager</p>
          <?php elseif ($user_role === 'clerk'): ?>
            <p class="p">Clerk</p>
          <?php endif; ?>

        <ul class="list-unstyled components mb-5">
          <li class="active">
            <a href="userhomedashboard.php"><span class="fa fa-home mr-3"></span> Home</a>
          </li>
          <?php if ($user_role === 'normal_user'): ?>
            <li><a href="folktalelist.php"><span class="fa fa-book mr-3"></span> Folktales List</a></li>
            <li><a href="bookmark.php"><span class="fa fa-bookmark mr-3"></span> Bookmarks</a></li>
          <?php elseif ($user_role === 'manager'): ?>
            <li><a href="generatereport.php"><span class="fa fa-chart-line mr-3"></span> Generate Report</a></li>
          <?php elseif ($user_role === 'clerk'): ?>
            <li><a href="clerkdashboard"><span class="fa fa-cog mr-3"></span> Folktales Management</a></li>
          <?php endif; ?>
        </ul>

        <div class="logout">
          <a href="#"><span class="fa fa-sign-out mr-3"></span> Sign Out</a>
        </div>
      </nav>
    <script src="../js/jquery.min.js"></script>
    <script src="../js/popper.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/main.js"></script>
  </body>
</html>
