<?php
session_start();

// Database connection
include 'dbconn.php';

// Check if an ID is passed as a parameter
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Folktale ID not provided!");
}

$folktaleId = $_GET['id'];

// Retrieve existing data for the given folktale ID
$query = "SELECT * FROM folktales WHERE folktaleID = ?";
$stmt = $conn->prepare($query);

// Check if the statement was prepared successfully
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}

// Bind the parameter
$stmt->bind_param("s", $folktaleId);

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Fetch the data
$folktale = $result->fetch_assoc();

if (!$folktale) {
    die("Folktale not found!");
}

// Close the statement
$stmt->close();

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve updated form data
    $title = $_POST['title'];
    $synopsis = $_POST['synopsis'];
    $state = $_POST['state'];
    $genre = $_POST['genre'];

    // Handle file upload
    $uploadDir = 'uploads/'; // Directory to store uploaded files
    $pdfPath = $folktale['pdfPath']; // Default to existing file path
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $fileName = basename($_FILES['file']['name']);
        $pdfPath = $uploadDir . $fileName;

        // Move the uploaded file to the upload directory
        if (!move_uploaded_file($_FILES['file']['tmp_name'], $pdfPath)) {
            die("File upload failed!");
        }
    }

    // Update data in the database
    $updateQuery = "UPDATE folktales SET title = ?, synopsis = ?, state = ?, genre = ?, pdfPath = ? WHERE folktaleID = ?";
    $updateStmt = $conn->prepare($updateQuery);

    if (!$updateStmt) {
        die("Error preparing update statement: " . $conn->error);
    }

    // Bind the parameters
    $updateStmt->bind_param("ssssss", $title, $synopsis, $state, $genre, $pdfPath, $folktaleId);

    // Execute the query
    if ($updateStmt->execute()) {
        echo "Data updated successfully!";
        // Redirect to a success page or back to the form
        header("Location: success_page.php");
        exit();
    } else {
        die("Error updating data: " . $updateStmt->error);
    }

    // Close the statement
    $updateStmt->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Folktales</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color:rgb(243, 247, 251) !important;
            margin: 0;
            padding: 0;
        }

        .content {
            margin-left: 270px;
            padding: 40px;
            
        }

        h1 {
            font-size: 1.8rem;
            font-weight: bold;
        }

        /* Custom styling for inputs, selects, and textarea */
        .custom-border {
            border: 1px solid rgba(103, 115, 127, 0.17) !important; /* Dark border with proper width */
            border-radius: 5px; /* Rounded corners */
        }

        textarea.custom-border {
            height: 200px; /* Bigger input space for the synopsis */
        }

        /* Styling for the upload button */
        .custom-upload-btn {
            background-color: #007bff; /* Primary blue color */
            color: #fff; /* White text */
            border: none; /* Remove default border */
            transition: background-color 0.3s ease; /* Smooth hover effect */
        }

        .custom-upload-btn:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }

        /* Form container styling */
        .form-section {
            padding: 20px; /* Add some padding around the form */
            background-color:rgb(254, 254, 254); /* White background */
            border: 1px rgba(123, 137, 152, 0.98); /* Visible border */
            border-radius: 5px; /* Rounded corners for the form container */
            font-size: 0.9rem;
            width: 1100px;
            box-shadow: 0px 4px 8px rgba(0, 0.1, 0, 0.1);
        }

        .btn-upload {
            margin-top: 10px;
            background-color: #4a90e2;
            color: white;
            border: 1px solid black;
        }

        .btn-upload:hover {
            background-color: #3577c8;
        }

        .btn-secondary, .btn-primary {
            padding: 10px 30px;
            font-size: 0.9rem;
        }

        /* Align upload input and button */
        .input-group .form-control {
            flex: 1;
        }

        .input-group .custom-upload-btn {
            flex: 0 0 auto;
        }
    </style>
</head>
<body>
<div id="content">
    <div class="container mt-3">
        <h1>Edit Folktales</h1>

        <div class="form-section mt-4">
            <form action="editfolktales.php?id=<?= htmlspecialchars($folktaleId) ?>" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="folktaleId" class="form-label">Folktale ID</label>
                        <input type="text" class="form-control custom-border" id="folktaleId" name="folktaleID" value="<?= htmlspecialchars($folktale['folktaleID']) ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="fileUpload" class="form-label">Upload File (.pdf)</label>
                        <div class="input-group">
                            <input type="file" class="form-control custom-border" id="fileUpload" name="file" accept=".pdf">
                            <button class="btn custom-upload-btn" type="button">Upload</button>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control custom-border" id="title" name="title" value="<?= htmlspecialchars($folktale['title']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="synopsis" class="form-label">Synopsis</label>
                    <textarea class="form-control custom-border" id="synopsis" name="synopsis" rows="3"><?= htmlspecialchars($folktale['synopsis']) ?></textarea>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="state" class="form-label">State</label>
                        <select class="form-select custom-border" id="state" name="state" required>
                            <option value="Johor" <?= $folktale['state'] == 'Johor' ? 'selected' : '' ?>>Johor</option>
                            <option value="Kedah" <?= $folktale['state'] == 'Kedah' ? 'selected' : '' ?>>Kedah</option>
                            <option value="Kelantan" <?= $folktale['state'] == 'Kelantan' ? 'selected' : '' ?>>Kelantan</option>
                            <option value="Malacca" <?= $folktale['state'] == 'Malacca' ? 'selected' : '' ?>>Malacca</option>
                            <option value="Negeri Sembilan" <?= $folktale['state'] == 'Negeri Sembilan' ? 'selected' : '' ?>>Negeri Sembilan</option>
                            <option value="Pahang" <?= $folktale['state'] == 'Pahang' ? 'selected' : '' ?>>Pahang</option>
                            <option value="Penang" <?= $folktale['state'] == 'Penang' ? 'selected' : '' ?>>Penang</option>
                            <option value="Perak" <?= $folktale['state'] == 'Perak' ? 'selected' : '' ?>>Perak</option>
                            <option value="Perlis" <?= $folktale['state'] == 'Perlis' ? 'selected' : '' ?>>Perlis</option>
                            <option value="Sabah" <?= $folktale['state'] == 'Sabah' ? 'selected' : '' ?>>Sabah</option>
                            <option value="Sarawak" <?= $folktale['state'] == 'Sarawak' ? 'selected' : '' ?>>Sarawak</option>
                            <option value="Selangor" <?= $folktale['state'] == 'Selangor' ? 'selected' : '' ?>>Selangor</option>
                            <option value="Terengganu" <?= $folktale['state'] == 'Terengganu' ? 'selected' : '' ?>>Terengganu</option>
                            <option value="Kuala Lumpur" <?= $folktale['state'] == 'Kuala Lumpur' ? 'selected' : '' ?>>Kuala Lumpur</option>
                            <option value="Labuan" <?= $folktale['state'] == 'Labuan' ? 'selected' : '' ?>>Labuan</option>
                            <option value="Putrajaya" <?= $folktale['state'] == 'Putrajaya' ? 'selected' : '' ?>>Putrajaya</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="genre" class="form-label">Genre</label>
                        <select class="form-select custom-border" id="genre" name="genre" required>
                            <option value="Teladan" <?= $folktale['genre'] == 'Teladan' ? 'selected' : '' ?>>Teladan</option>
                            <option value="Adventure" <?= $folktale['genre'] == 'Adventure' ? 'selected' : '' ?>>Adventure</option>
                            <option value="Fantasy" <?= $folktale['genre'] == 'Fantasy' ? 'selected' : '' ?>>Fantasy</option>
                            <!-- Add more genres as needed -->
                        </select>
                    </div>
                </div>

                <div class="text-end">
                    <button type="button" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
