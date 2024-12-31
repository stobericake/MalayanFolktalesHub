<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Folktales</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-container h2 {
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color: #4a90e2;
            border: none;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-container">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" href="#">Add Folktales</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Edit Folktales</a>
            </li>
        </ul>

        <h2>Edit Folktales</h2>
        <form action="upload_folktale.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="folktaleId" class="form-label">Folktales ID</label>
                <input type="text" class="form-control" id="folktaleId" name="folktale_id" placeholder="Folktales ID">
            </div>

            <div class="mb-3">
                <label for="folktaleTitle" class="form-label">Title</label>
                <input type="text" class="form-control" id="folktaleTitle" name="title" placeholder="Enter Folktales Title" required>
            </div>

            <div class="mb-3">
                <label for="synopsis" class="form-label">Synopsis</label>
                <textarea class="form-control" id="synopsis" name="synopsis" rows="3" placeholder="Enter Folktales Synopsis"></textarea>
            </div>

            <div class="mb-3">
                <label for="state" class="form-label">State</label>
                <select class="form-select" id="state" name="state" required>
                    <option selected>Select state</option>
                    <option value="State1">State1</option>
                    <option value="State2">State2</option>
                    <!-- Add more states as needed -->
                </select>
            </div>

            <div class="mb-3">
                <label for="genre" class="form-label">Genre</label>
                <select class="form-select" id="genre" name="genre" required>
                    <option selected>Select genre</option>
                    <option value="Adventure">Adventure</option>
                    <option value="Fantasy">Fantasy</option>
                    <!-- Add more genres as needed -->
                </select>
            </div>

            <div class="mb-3">
                <label for="fileUpload" class="form-label">Upload File (.pdf)</label>
                <input class="form-control" type="file" id="fileUpload" name="file" accept=".pdf" required>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="reset" class="btn btn-secondary">Cancel</button>
        </form>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
