<?php

session_start();
include '../dbconn.php';
$userID = isset($_SESSION['userID']) ? $_SESSION['userID'] : null;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $synopsis = $_POST['synopsis'];
    $state = $_POST['state'];
    $genre = $_POST['genre'];
    $uploadDir = '../uploads/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true); 
    }
    $pdfPath = null;
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $fileName = basename($_FILES['file']['name']);
        $pdfPath = $uploadDir . $fileName;

        if (!move_uploaded_file($_FILES['file']['tmp_name'], $pdfPath)) {
            die("File upload failed!");
        }
    }

    try {
        $query = "INSERT INTO folktales (title, synopsis, state, genre, pdfPath, clerkID) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        
        if (!$stmt) {
            throw new Exception("Failed to prepare the statement: " . $conn->error);
        }
    
        $stmt->bind_param("ssssss",  $title, $synopsis, $state, $genre, $pdfPath, $userID);
    
        if ($stmt->execute()) {
            $_SESSION['message'] = "Data updated successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error updating data: " . $updateStmt->error;
            $_SESSION['message_type'] = "error";
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url('../../assets/background.png') no-repeat center center/cover;
            background-size: cover;
            padding-top: 100px;
        }
        .container {
            width: 100%;
            max-width: 1200px;
            padding: 20px;
            margin-bottom: 50px;
        }

        .container h1 {
            font-size: 1.9rem;
            font-weight: bold;
        }

        .custom-border {
            border: 1px solid rgba(103, 115, 127, 0.17) !important; 
            border-radius: 5px;
        }

        textarea.custom-border {
            height: 200px; 
        }


        .form-section {
            padding: 20px;
            background-color: rgba(195, 223, 230, 0.9);
            border-radius: 8px;
            font-size: 0.9rem;
            width: 100%;
            box-shadow: 0px 4px 8px rgba(0, 0.1, 0, 0.1);
        }

        .btn-secondary, .btn-primary {
            padding: 10px 30px;
            font-size: 0.9rem;
            border-radius: 20px;
        }
        .btn-primary {
            background-color: #007bff;
            color: #fff;
            border: none; 
            transition: background-color 0.3s ease; 
            border-radius: 20px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-secondary {
            background-color:rgb(147, 151, 154);
            color: #fff;
            border: none; 
            transition: background-color 0.3s ease; 
            border-radius: 20px;
        }

        .btn-secondary:hover {
            background-color:rgb(89, 93, 97); 
        }

        .input-group .form-control {
            flex: 1;
        }

        .input-group .custom-upload-btn {
            flex: 0 0 auto;
        }
        
        .form-control.custom-border {
            background-color:rgb(236, 238, 240);
            color: rgb(60, 62, 63);
        }
        .form-select.custom-border {
            background-color:rgb(236, 238, 240);
            color: rgb(60, 62, 63);
        }
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            text-align: center;
        }

        .popup.success {
            border: 1px solid rgb(175, 227, 177);
        }

        .popup.error {
            border: 1px solid rgb(249, 103, 93);
        }

        .popup-icon {
            font-size: 50px;
            margin-bottom: 10px;
        }

        .popup.success .popup-icon {
            color: rgb(175, 227, 177);
        }

        .popup.error .popup-icon {
            color: rgb(249, 103, 93);
        }

        .popup-close {
            margin-top: 10px;
            padding: 5px 10px;
            background-color: rgb(175, 227, 177);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .popup-close:hover {
            background-color: #45a049;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
        
    </style>
</head>
<body>
<?php include '../header.php'; ?>
<div class="container">
    <div class="form-section">
        <h1>Add Folktales</h1>
        <form id="folktaleForm" action="addfolktales.php" method="POST" enctype="multipart/form-data">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control custom-border" id="title" name="title" placeholder="Insert title here.." required>
                </div>
                <div class="col-md-6">
                    <label for="fileUpload" class="form-label">Upload File (.pdf)</label>
                    <div class="input-group">
                        <input type="file" class="form-control custom-border" id="fileUpload" name="file" accept=".pdf">
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
                        <select class="form-select custom-border" id="genre" name="genre" required>
                            <option value="Alegori">Alegori</option>
                            <option value="Cerita rakyat">Cerita rakyat</option>
                            <option value="Dongeng">Dongeng</option>
                            <option value="Epik">Epik</option>
                            <option value="Fabel">Fabel</option>
                            <option value="Jenaka">Jenaka</option>
                            <option value="Kanak-Kanak">Kanak-Kanak</option>
                            <option value="Lagenda">Lagenda</option>
                            <option value="Mitos">Mitos</option>
                            <option value="Moral">Moral</option>
                            <option value="Pengembaraan">Pengembaraan</option>
                            <option value="Sejarah">Sejarah</option>
                            <option value="Seram">Seram</option>
                            <option value="Teladan">Teladan</option>
                        </select>
                    </div>
                </div>
           

            <div class="text-end">
                <button type="button" class="btn-secondary" onclick="window.location.href = 'clerklisting.php'">Cancel</button>
                <button type="button" class="btn-primary" data-bs-toggle="modal" data-bs-target="#confirmationModal">Add</button>
            </div>
        </form>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to add this folktale?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn-primary" id="confirmAdd">Confirm</button>
            </div>
        </div>
    </div>
</div>

<div class="overlay" id="overlay"></div>
<div class="popup" id="popup">
    <div class="popup-icon">🎉</div>
    <p id="popup-message"></p>
    <button class="popup-close" onclick="closePopup()">Close</button>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('confirmAdd').addEventListener('click', function () {
        document.getElementById('folktaleForm').submit();
    });

        function showPopup(message, type) {
        const popup = document.getElementById('popup');
        const overlay = document.getElementById('overlay');
        const popupMessage = document.getElementById('popup-message');

        popupMessage.textContent = message;
        popup.className = `popup ${type}`;
        popup.style.display = 'block';
        overlay.style.display = 'block';
    }

    function closePopup() {
        const popup = document.getElementById('popup');
        const overlay = document.getElementById('overlay');
        popup.style.display = 'none';
        overlay.style.display = 'none';

        window.location.href = 'clerklisting.php';    
    }

    <?php if (isset($_SESSION['message'])): ?>
        showPopup("<?= $_SESSION['message'] ?>", "<?= $_SESSION['message_type'] ?>");
        <?php
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
        ?>
    <?php endif; ?>
</script>
</body>
</html>