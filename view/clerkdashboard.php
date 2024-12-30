<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Page</title>
    </head>
    <body>
    <!-- code untuk include sidebar -->
    <?php include 'sidebar.php'; ?> 
    <!-- Page Content -->
    <div id="content" class="p-4 p-md-5 pt-5">
        <h2>Welcome, <?php echo ucfirst($user_role); ?>!</h2>
        <p>This is your role-based dashboard.</p>
      </div>
</body>

