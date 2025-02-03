<?php
session_start();
// Database connection
include '../dbconn.php';

// Ensure $userID is set and valid
$userID = isset($_SESSION['userID']) ? $_SESSION['userID'] : null;

// Fetch user data
$query = "SELECT * FROM user WHERE userID = '$userID'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $user_data = mysqli_fetch_assoc($result);
} else {
    echo "Error fetching user data.";
    exit;
}

// Fetch total bookmarks
$bookmarkQuery = "SELECT COUNT(*) AS total_bookmarks FROM bookmark WHERE userID = '$userID'";
$bookmarkResult = mysqli_query($conn, $bookmarkQuery);
$bookmarkData = mysqli_fetch_assoc($bookmarkResult);
$totalBookmarks = $bookmarkData['total_bookmarks'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-image: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('../../assets/background.png');
            background-size: cover;
            background-position: center;
            height: 110vh;
            padding-top: 120px;
            padding-bottom: 120px;
        }

        .profile-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            padding: 30px;
            max-width: 600px;
            margin: auto;
        }

        .profile-card .avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: #FFF5E0;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 48px;
            color: #666;
            margin: -60px auto 20px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .profile-card h2 {
            font-size: 20px;
            color: #333;
            text-align: center;
        }

        .profile-card .form-label {
            font-weight: bold;
            color: #555;
        }

        .profile-card .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 30px;
            transition: background-color 0.3s ease;
        }

        .profile-card .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <?php include '../header.php'; ?>

    <!-- Main Content -->
    <div class="container mt-3 mb-7">
        <div class="profile-card">
            <!-- Default Avatar Icon -->
            <div class="avatar">
                <span>&#128100;</span> <!-- Person Icon -->
            </div>
            <h2><?php echo htmlspecialchars($user_data['name']); ?></h2>
            <!-- Edit Profile Form -->
            <form id="profileForm">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="text" class="form-control" id="phone_number" value="<?php echo htmlspecialchars($user_data['phone_number'] ?? ''); ?>">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="password" placeholder="Enter new password">
                </div>
                <div class="mb-3">
                    <label for="confirmPassword" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm new password">
                </div>
                <button type="button" class="btn btn-primary w-100" onclick="confirmUpdate()">Update Profile</button>
            </form>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">Confirm Update</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to update your profile?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="updateProfile()">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmUpdate() {
            // Validate password match
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            if (password !== confirmPassword) {
                alert("Passwords do not match.");
                return;
            }

            // Show confirmation modal
            const modal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            modal.show();
        }

        function updateProfile() {
            const phone_number = document.getElementById('phone_number').value;
            const password = document.getElementById('password').value;

            // Send data to server using fetch
            fetch('update_profile.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    phone_number: phone_number,
                    password: password
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Profile updated successfully.");
                    location.reload(); // Reload the page to reflect changes
                } else {
                    alert("Failed to update profile: " + data.message);
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("An error occurred while updating the profile.");
            });
        }
    </script>
</body>
</html>