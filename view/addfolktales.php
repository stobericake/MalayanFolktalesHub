<?php
session_start();

// Database connection
include 'dbconn.php';

// Initialize result variable
$result = null;

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Folktales</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .sidebar {
            background-color: #d9eaf3;
            height: 100vh;
            padding: 20px;
            width: 250px;
            position: fixed;
        }

        .sidebar h2 {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            font-weight: bold;
            color: #4a4a4a;
        }

        .sidebar h3 {
            font-size: 1rem;
            color: #4a4a4a;
            font-weight: bold;
        }

        .content {
            margin-left: 270px;
            padding: 40px;
        }

        .tab-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .tab-header h1 {
            font-size: 1.8rem;
            font-weight: bold;
        }

        .nav-tabs .nav-link.active {
            border-color: #4a90e2;
            color: #4a90e2;
        }

        .form-section {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 1px;
            background-color: #fff;
        }

        .btn-upload {
            margin-top: 10px;
            background-color: #4a90e2;
            color: white;
            border: none;
        }

        .btn-upload:hover {
            background-color: #3577c8;
        }

        .btn-secondary, .btn-primary {
            padding: 10px 30px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>MalayanFolktalesHub</h2>
        <h3>Clerk Management</h3>
        <hr>
        <p>Manage Folktales</p>
    </div>

    <div class="content">
        <div class="tab-header">
            <h1>Add Folktales</h1>
        </div>


        <div class="form-section mt-4">
            <form action="edit_folktale_handler.php" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="folktaleId" class="form-label">Folktales ID</label>
                        <input type="text" class="form-control" id="folktaleId" name="folktale_id" placeholder="Folktales ID" >
                    </div>
                    <div class="col-md-6">
                        <label for="fileUpload" class="form-label">Upload File (.pdf)</label>
                        <div class="input-group">
                            <input type="file" class="form-control" id="fileUpload" name="file" accept=".pdf" required>
                            <button class="btn btn-upload" type="button">Upload</button>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="Enter Folktales Title" required>
                </div>

                <div class="mb-3">
                    <label for="synopsis" class="form-label">Synopsis</label>
                    <textarea class="form-control" id="synopsis" name="synopsis" rows="3" placeholder="Enter Folktales Synopsis"></textarea>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="state" class="form-label">State</label>
                        <select class="form-select" id="state" name="state" required>
                            <option selected>Select state</option>
                            <option value="State1">State1</option>
                            <option value="State2">State2</option>
                            <!-- Add more states as needed -->
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="genre" class="form-label">Genre</label>
                        <select class="form-select" id="genre" name="genre" required>
                            <option selected>Select genre</option>
                            <option value="Adventure">Adventure</option>
                            <option value="Fantasy">Fantasy</option>
                            <!-- Add more genres as needed -->
                        </select>
                    </div>
                </div>

                <div class="text-end">
                    <button type="button" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
