<?php
session_start();

// Database connection
include 'dbconn.php';
if (!isset($_SESSION['role'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit;
}

$role = $_SESSION['role'];
// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $folktaleId = $_POST['folktaleID'];
    $title = $_POST['title'];
    $synopsis = $_POST['synopsis'];
    $state = $_POST['state'];
    $genre = $_POST['genre'];

    // Handle file upload
    $uploadDir = 'uploads/'; // Directory to store uploaded files
    $pdfPath = null;
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $fileName = basename($_FILES['file']['name']);
        $pdfPath = $uploadDir . $fileName;

        // Move the uploaded file to the upload directory
        if (!move_uploaded_file($_FILES['file']['tmp_name'], $pdfPath)) {
            die("File upload failed!");
        }
    }

    try {
        // Insert data into the database
        $query = "INSERT INTO folktales (folktaleID, title, synopsis, state, genre, pdfPath) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        
        if (!$stmt) {
            throw new Exception("Failed to prepare the statement: " . $conn->error);
        }
    
        // Bind parameters
        $stmt->bind_param("ssssss", $folktaleId, $title, $synopsis, $state, $genre, $pdfPath);
    
        // Execute the statement
        if ($stmt->execute()) {
            echo "Data inserted successfully!";
            // Redirect to a success page or back to the form
            header("Location: success_page.php");
            exit();
        } else {
            throw new Exception("Failed to execute the statement: " . $stmt->error);
        }
    } catch (Exception $e) {
        die("Error inserting data: " . $e->getMessage());
    }
    
}
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
<div class="d-flex">
        <!-- Include Sidebar -->
        <?php include 'sidebaar2.php'; ?>

        <!-- Main Content -->
        <div class="container-fluid p-4">
        <h1>Add Folktales</h1>

        <div class="form-section mt-4">
            <form action="addfolktales.php" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control custom-border" id="title" name="title" placeholder="Insert title here.." required>
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
                    <label for="synopsis" class="form-label">Synopsis</label>
                    <textarea class="form-control custom-border" id="synopsis" name="synopsis" placeholder="Insert folktale synopsis here.." rows="3"></textarea>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                    <label for="state" class="form-label">State</label>
                    <select class="form-select custom-border" id="state" name="state" required>
                        <option value="" disabled selected>Select a state</option>
                        <option value="Johor">Johor</option>
                        <option value="Kedah">Kedah</option>
                        <option value="Kelantan">Kelantan</option>
                        <option value="Malacca">Malacca</option>
                        <option value="Negeri Sembilan">Negeri Sembilan</option>
                        <option value="Pahang">Pahang</option>
                        <option value="Penang">Penang</option>
                        <option value="Perak">Perak</option>
                        <option value="Perlis">Perlis</option>
                        <option value="Sabah">Sabah</option>
                        <option value="Sarawak">Sarawak</option>
                        <option value="Selangor">Selangor</option>
                        <option value="Terengganu">Terengganu</option>
                        <option value="Kuala Lumpur">Kuala Lumpur (FT)</option>
                        <option value="Labuan">Labuan (FT)</option>
                        <option value="Putrajaya">Putrajaya (FT)</option>
                    </select>
                    </div>
                    <div class="col-md-6">
                        <label for="genre" class="form-label">Genre</label>
                        <input type="text" class="form-control custom-border" id="genre" name="genre" placeholder="Insert genre here.." required>
                    </div>
                </div>

                <div class="text-end">
                    <button type="button" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
