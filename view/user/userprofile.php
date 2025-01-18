<?php
session_start();
// Database connection
include '../dbconn.php';
// Ensure $userID is set and valid
$userID = isset($_SESSION['userID']) ? $_SESSION['userID'] : null;
$query = "SELECT * FROM user WHERE userID = '$userID'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $user_data = mysqli_fetch_assoc($result);
} else {
    echo "Error fetching user data.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
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
            padding-top:100px;
            height: 100vh;
        }

        /* Main Content Area */
        .container-fluid {
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        /* Profile Card Styles */
        .profile-card {
            background: #C3DFE6;
            border-radius: 30px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            text-align: center;
            width: 500px;
            padding: 40px 30px;
        }

        .profile-card .avatar {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background-color: #FFF5E0;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 64px;
            color: #666;
            margin: -70px auto 15px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .profile-card h2 {
            font-size: 24px;
            margin: 15px 0 8px;
        }

        .profile-card p {
            font-size: 14px;
            margin: 8px 0;
            color: #444;
        }

        .profile-card .btn {
            display: inline-block;
            margin-top: 20px;
            background: #FFF5E0; 
            color: black; 
            padding: 10px 20px;
            border-radius: 30px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            border: none;
            transition: background 0.3s ease;
            cursor: pointer;
        }

        .profile-card .btn:hover {
            background: #FFE8B3;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <?php include '../header.php'; ?>

        <!-- Main Content -->
        <div class="container-fluid pt-4">
            <div class="profile-card">
                <!-- Default Avatar Icon -->
                <div class="avatar">
                    <span>&#128100;</span> <!-- Person Icon -->
                </div>
                <h2><?php echo htmlspecialchars($user_data['name']); ?></h2>
                <p><?php echo htmlspecialchars($user_data['email']); ?></p>
                <p>
                    <?php echo ucfirst($role); ?>
                </p>
                <p>New York, United States</p>
                <p>Web Producer - Web Specialist<br>Columbia University - New York</p>

                <!-- Update and Delete Buttons -->
                <div class="actions">
                    <button class="btn" onclick="location.href='update_profile.php'">Update Profile</button>
                    <button class="btn" onclick="deleteProfile()">Delete Profile</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function deleteProfile() {
            if (confirm("Are you sure you want to delete your profile?")) {
                location.href = "delete_profile.php";
            }
        }
    </script>
</body>
</html>


