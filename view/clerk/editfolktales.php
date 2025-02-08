
<?php
session_start();
include '../dbconn.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Folktale ID not provided!");
}

$folktaleId = $_GET['id'];

$query = "SELECT * FROM folktales WHERE folktaleID = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param("s", $folktaleId);

$stmt->execute();
$result = $stmt->get_result();
$folktale = $result->fetch_assoc();

if (!$folktale) {
    die("Folktale not found!");
}

$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $synopsis = $_POST['synopsis'];
    $state = $_POST['state'];
    $genre = $_POST['genre'];

    $uploadDir = 'uploads/'; 
    $pdfPath = $folktale['pdfPath']; 
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $fileName = basename($_FILES['file']['name']);
        $pdfPath = $uploadDir . $fileName;

        if (!move_uploaded_file($_FILES['file']['tmp_name'], $pdfPath)) {
            die("File upload failed!");
        }
    }

    $updateQuery = "UPDATE folktales SET title = ?, synopsis = ?, state = ?, genre = ?, pdfPath = ? WHERE folktaleID = ?";
    $updateStmt = $conn->prepare($updateQuery);

    if (!$updateStmt) {
        die("Error preparing update statement: " . $conn->error);
    }

    $updateStmt->bind_param("ssssss", $title, $synopsis, $state, $genre, $pdfPath, $folktaleId);

    if ($updateStmt->execute()) {
        $_SESSION['message'] = "Data updated successfully!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error updating data: " . $updateStmt->error;
        $_SESSION['message_type'] = "error";
    }

    $updateStmt->close();
    header("Location: editfolktales.php?id=" . $folktaleId);
    exit();
}
$conn->close();
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
            padding-top: 100px;
            position: relative; 
            background: url('../../assets/background.png') no-repeat center center/cover;
            background-size: cover;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            padding: 20px;
            margin-bottom: 80px;
        }

        .container h1 {
            font-size: 1.9rem;
            font-weight: bold;
        }

        textarea.custom-border {
            height: 150px; 
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
        <h1>Edit Folktales</h1>
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
                            <option value="Alegori" <?= $folktale['genre'] == 'Alegori' ? 'selected' : '' ?>>Alegori</option>
                            <option value="Cerita rakyat" <?= $folktale['genre'] == 'Cerita rakyat' ? 'selected' : '' ?>>Cerita rakyat</option>
                            <option value="Dongeng" <?= $folktale['genre'] == 'Dongeng' ? 'selected' : '' ?>>Dongeng</option>
                            <option value="Epik" <?= $folktale['genre'] == 'Epik' ? 'selected' : '' ?>>Epik</option>
                            <option value="Fabel" <?= $folktale['genre'] == 'Fabel' ? 'selected' : '' ?>>Fabel</option>
                            <option value="Jenaka" <?= $folktale['genre'] == 'Jenaka' ? 'selected' : '' ?>>Jenaka</option>
                            <option value="Kanak-Kanak" <?= $folktale['genre'] == 'Kanak-Kanak' ? 'selected' : '' ?>>Kanak-Kanak</option>
                            <option value="Lagenda" <?= $folktale['genre'] == 'Lagenda' ? 'selected' : '' ?>>Lagenda</option>
                            <option value="Mitos" <?= $folktale['genre'] == 'Mitos' ? 'selected' : '' ?>>Mitos</option>
                            <option value="Moral" <?= $folktale['genre'] == 'Moral' ? 'selected' : '' ?>>Moral</option>
                            <option value="Pengembaraan" <?= $folktale['genre'] == 'Pengembaraan' ? 'selected' : '' ?>>Pengembaraan</option>
                            <option value="Sejarah" <?= $folktale['genre'] == 'Sejarah' ? 'selected' : '' ?>>Sejarah</option>
                            <option value="Seram" <?= $folktale['genre'] == 'Seram' ? 'selected' : '' ?>>Seram</option>
                            <option value="Teladan" <?= $folktale['genre'] == 'Teladan' ? 'selected' : '' ?>>Teladan</option>
                        </select>
                    </div>
                </div>

                <div class="text-end">
                    <button type="button" class="btn-secondary" onclick="window.location.href = 'clerklisting.php'">Cancel</button>
                    <button type="submit" class="btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Popup HTML -->
<div class="overlay" id="overlay"></div>
<div class="popup" id="popup">
    <div class="popup-icon">🎉</div>
    <p id="popup-message"></p>
    <button class="popup-close" onclick="closePopup()">Close</button>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>