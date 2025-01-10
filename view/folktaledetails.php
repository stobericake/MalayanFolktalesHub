<?php
session_start();

// Database connection
include 'dbconn.php';

// Get folktale ID from query string
$folktalesID = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch folktale details from the database
$sql = "SELECT * FROM folktales WHERE folktaleID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $folktalesID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $folktale = $result->fetch_assoc();
} else {
    die("Folktale not found.");
}

$userID = 12; 

$sql2 = "SELECT pdfID, pdfPath FROM test WHERE pdfID = 1"; // Replace `your_table` with your table name
$result2 = $conn->query($sql2);

if ($result2->num_rows > 0) {
    $row = $result2->fetch_assoc();
    $pdfData = base64_encode($row['pdfPath']); // Convert the blob to base64
} else {
    die("PDF not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Folktales List</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .comments-section {
            max-height: 400px;
            overflow-y: auto;
        }
        .comment-box {
            resize: none;
        }
        .folktale-header {
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
        }
        .tabs {
            margin-top: 5px;
        }
        .nav-tabs .nav-link {
            color: black;
            background-color: #f8f9fa;
            border-bottom: 1px solid #BF2C24;
            margin-right: 2px;
            border-radius: 5px;
        }
        .nav-tabs .nav-link.active {
            color: #BF2C24;
            background-color: #fff;
            border-color: #BF2C24;
            border-bottom: 2px solid #BF2C24;
        }
        .nav-tabs .nav-link:hover {
            color: #BF2C24;
            background-color: #e2e6ea;
        }
        .btn-custom {
            background-color: #EFEFF4;
            border-color: transparent;
            border-radius: 0;
            padding: 10px;
            max-height: 50px;
            width: 150px;
        }
        .profile-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .tab-pane p {
            white-space: pre-wrap; 
            word-wrap: break-word; 
            overflow: visible; 
        }
        .description {
        max-height: none; 
        overflow: visible;
        padding: 10px; 
        background-color: #f8f9fa; 
        border-radius: 5px; 
    }
        #pdf-render {
            width: 100%;
            margin: 10px auto;
            border: 0px;
        }
        .controls {
            text-align: center;
            margin: 10px;
        }
        button {
            padding: 10px;
            margin: 0 5px;
        }

    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>
<div id="content" class="d-flex">
    <div class="container flex-grow-1">
        <!-- Folktale Header -->
        <div class="folktale-header">
            <h2><?php echo htmlspecialchars($folktale['title']); ?></h2>
            <p><i class="bi bi-bookmark"></i> <?php echo htmlspecialchars($folktale['genre']); ?> | <i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($folktale['state']); ?></p>
        </div>

        <!-- Folktale Statistics -->
        <div class="row">
            <div class="col-md-4">
                <p><i class="bi bi-eye-fill"></i><strong> 5.6M</strong> Views</p>
            </div>
            <div class="col-md-4">
                <p><i class="bi bi-star-fill"></i><strong> 4.8</strong> Bookmarks</p>
            </div>
        </div>
        <div class="row mb-3">
        <div class="col-md-2">
            <button class="btn-custom download-btn" data-folktales-id="<?php echo $pdfID; ?>">
                <i class="bi bi-box-arrow-down"></i> Download
            </button>
        </div>
            <div class="col-md-2">
                <button class="btn-custom bookmark-btn" data-folktales-id="<?php echo $folktalesID; ?>" data-user-id="<?php echo $userID; ?>">
                    <i class="bi bi-bookmark-star"></i> Bookmark
                </button>
            </div>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs tabs mb-3">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#introduction">Synopsis</a>
            </li>
        </ul>

        <div class="tab-pane fade show active" id="introduction">
    <h4>Description</h4>
    <div style="white-space: pre-wrap; word-wrap: break-word;">
        <?php echo nl2br(htmlspecialchars($folktale['synopsis'], ENT_QUOTES, 'UTF-8'));
; ?>
    </div>
</div>

        <!-- Download Modal -->
        <div class="modal fade" id="downloadModal" tabindex="-1" aria-labelledby="downloadModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="downloadModalLabel">Folktale Preview</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <canvas id="pdf-render"></canvas>
                    </div>
                    <div class="modal-footer">
    <?php
    if (isset($row) && $pdfData) {
        $fileName = "file_" . $row["pdfID"] . ".pdf";
        $filePath = "uploads/" . $fileName;

        // Save the PDF to a file if it doesn't exist
        if (!file_exists($filePath)) {
            file_put_contents($filePath, base64_decode($pdfData));
        }

        // Display the download button with a valid link
        echo "<a href='$filePath' download='$fileName' class='btn btn-primary'>Download</a>";
    } else {
        echo "<p>No file available for download.</p>";
    }
    ?>   

  
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    <!-- Bookmark Modal -->
    <div class="modal fade" id="bookmarkModal" tabindex="-1" aria-labelledby="bookmarkModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookmarkModalLabel">Notification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="bookmarkMessage">Folktale added to bookmarks!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Comments Section -->
    <div class="comments-section mt-5 p-4 border rounded">
        <h5 class="mb-4">Comments <span class="badge bg-secondary">14</span></h5>
        <div class="comment-list mb-4">
            <!-- Example Comments -->
            <div class="comment-item d-flex align-items-start mb-4">
                <img src="profile1.jpg" alt="User Profile" class="rounded-circle me-3" width="50">
                <div>
                    <strong>Sharifah Nur Ain</strong>
                    <p>GREAT STORY. LOVED THE CHARACTERS. HIGHLY RECOMMENDED.</p>
                </div>
            </div>
        </div>
        <!-- Add Comment -->
        <div class="comment-form" style="background-color:rgb(255, 240, 249); border-radius: 8px; padding: 15px;">
            <div class="d-flex align-items-start mb-3">
                <img src="user-profile.jpg" alt="Your Profile" class="rounded-circle me-3" width="50">
                <textarea class="form-control" placeholder="Leave a comment" style="background-color: #fafafa; border-color: #ccc;"></textarea>
            </div>
            <button class="btn btn-primary">Post</button>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const bookmarkButtons = document.querySelectorAll('.bookmark-btn');

    bookmarkButtons.forEach(button => {
        button.addEventListener('click', function () {
            const folktalesID = this.dataset.folktalesId;
            const userID = this.dataset.userId;

            // Send AJAX request to bookmark the folktale
            fetch('add_bookmark.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ folktalesID, userID })
            })
            .then(response => response.json())
            .then(data => {
                const modalEl = document.getElementById('bookmarkModal');
                const modalMessage = document.getElementById('bookmarkMessage');
                const modal = new bootstrap.Modal(modalEl);

                if (data.success) {
                    modalMessage.textContent = 'Folktale added to bookmarks!';
                } else {
                    modalMessage.textContent = 'Failed to add folktale: ' + data.message;
                }
                modal.show();
            })
            .catch(error => {
                console.error('Error:', error);
                const modalEl = document.getElementById('bookmarkModal');
                const modalMessage = document.getElementById('bookmarkMessage');
                const modal = new bootstrap.Modal(modalEl);
                modalMessage.textContent = 'An error occurred while adding the folktale.';
                modal.show();
             });
        });
    });
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const downloadButtons = document.querySelectorAll('.download-btn');

        downloadButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Retrieve PDF data
                const pdfData = 'data:application/pdf;base64,<?php echo $pdfData; ?>';
                const pdfjsLib = window['pdfjs-dist/build/pdf'];

                // PDF.js setup
                pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';
                let pdfDoc = null;
                let currentPage = 1;
                const canvas = document.getElementById('pdf-render');
                const ctx = canvas.getContext('2d');

                // Render a specific page
                function renderPage(pageNum) {
                    pdfDoc.getPage(pageNum).then(page => {
                        const viewport = page.getViewport({ scale: 1.5 }); // Adjust scale for better readability
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;

                        const renderContext = {
                            canvasContext: ctx,
                            viewport: viewport
                        };
                        page.render(renderContext);
                    });
                }

                // Load the PDF and display the first page
                pdfjsLib.getDocument(pdfData).promise.then(doc => {
                    pdfDoc = doc;
                    renderPage(currentPage);
                    // Show the modal
                    const modal = new bootstrap.Modal(document.getElementById('downloadModal'));
                    modal.show();
                }).catch(error => {
                    console.error('Error loading PDF:', error);
                    alert('Failed to load the PDF. Please try again.');
                });
            });
        });
    });
</script>
    
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>

</body>
</html>
<?php
$conn->close();
?>
