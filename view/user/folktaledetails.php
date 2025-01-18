<?php
 // Ensure session is started
include '../dbconn.php';

$folktalesID = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch folktale details from the database
$sql = "SELECT * FROM folktales WHERE folktaleID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $folktalesID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $folktale = $result->fetch_assoc();
    $pdfData = base64_encode($folktale['pdfPath']); // Convert the blob to base64
} else {
    die("Folktale not found.");
}

$userID = isset($_SESSION['userID']) ? $_SESSION['userID'] : null;

$commentsQuery = "
    SELECT c.commentID, c.content, c.datePosted, u.name 
    FROM comment c 
    JOIN user u ON c.userID = u.userID 
    WHERE c.folktaleID = ?
    ORDER BY c.datePosted DESC";
$stmt = $conn->prepare($commentsQuery);
$stmt->bind_param("i", $folktalesID);
$stmt->execute();
$commentsResult = $stmt->get_result();

// Handle new comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_content'])) {
    $commentContent = trim($_POST['comment_content']);
    if (!empty($commentContent) && $userID) {
        $insertCommentQuery = "INSERT INTO comment (content, datePosted, userID, folktaleID) VALUES (?, NOW(), ?, ?)";
        $insertStmt = $conn->prepare($insertCommentQuery);
        $insertStmt->bind_param("sii", $commentContent, $userID, $folktalesID);
        $insertStmt->execute();
        $insertStmt->close();

        // Refresh the page to display the new comment
        header("Location: ?id=$folktalesID");
        exit;
    }
}

// Check if the current user has bookmarked this folktale
$isBookmarked = false;
if ($userID) {
    $bookmarkCheckQuery = "SELECT COUNT(*) AS isBookmarked FROM bookmark WHERE userID = ?";
    $bookmarkStmt = $conn->prepare($bookmarkCheckQuery);
    $bookmarkStmt->bind_param("i", $userID);
    $bookmarkStmt->execute();
    $bookmarkResult = $bookmarkStmt->get_result();
    $bookmarkData = $bookmarkResult->fetch_assoc();
    $isBookmarked = ($bookmarkData['isBookmarked'] > 0);
    $bookmarkStmt->close();
}

// Get total number of bookmarks for this folktale
$totalBookmarksQuery = "SELECT COUNT(*) AS totalBookmarks FROM bookmark_folktale WHERE folktalesID = ?";
$totalBookmarksStmt = $conn->prepare($totalBookmarksQuery);
$totalBookmarksStmt->bind_param("i", $folktalesID);
$totalBookmarksStmt->execute();
$totalBookmarksResult = $totalBookmarksStmt->get_result();
$totalBookmarksData = $totalBookmarksResult->fetch_assoc();
$totalBookmarks = $totalBookmarksData['totalBookmarks'];
$totalBookmarksStmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Folktales Detail</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body{
            padding-top: 100px;
        }
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
<div id="content" class="d-flex">
<?php include '../header.php'; ?>
    <div class="container flex-grow-1">
        <!-- Folktale Header -->
        <div class="folktale-header">
            <h2><?php echo htmlspecialchars($folktale['title']); ?></h2>
            <p><i class="bi bi-bookmark"></i> <?php echo htmlspecialchars($folktale['genre']); ?> | <i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($folktale['state']); ?></p>
        </div>

        <!-- Folktale Statistics -->
        <div class="col-md-4">
            <p><i class="bi bi-star-fill"></i><strong> <span class="bookmark-count"><?php echo $totalBookmarks; ?></span></strong> Bookmarks</p>
        </div>
        <div class="row mb-3">
        <div class="col-md-2">
            <button class="btn-custom download-btn" data-folktales-id="<?php echo $folktalesID; ?>">
                <i class="bi bi-box-arrow-down"></i> Download
            </button>
        </div>
        <div class="col-md-2">
            <button class="btn-custom bookmark-btn" data-folktales-id="<?php echo $folktalesID; ?>" data-user-id="<?php echo isset($_SESSION['userID']) ? $_SESSION['userID'] : ''; ?>">
                <i class="bi <?php echo $isBookmarked ? 'bi-bookmark-star-fill' : 'bi-bookmark-star'; ?>"></i> 
                <span class="bookmark-count"><?php echo $totalBookmarks; ?></span> Bookmarks
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
                    if (isset($folktale) && $pdfData) {
                        $fileName = "file_" . $folktale["title"] . ".pdf";
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

    <!-- Login Prompt Modal -->
    <div class="modal fade" id="loginPromptModal" tabindex="-1" aria-labelledby="loginPromptModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginPromptModalLabel">Login Required</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>You need to be logged in to perform this action. Please log in or sign up.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="../login.php" class="btn btn-primary">Login</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Comments Section -->
    <div class="comments-section mt-5 p-4 border rounded">
        <h5 class="mb-4">Comments <span class="badge bg-secondary"><?php echo $commentsResult->num_rows; ?></span></h5>
        <div class="comment-list mb-4">
            <?php if ($commentsResult->num_rows > 0): ?>
                <?php while ($comment = $commentsResult->fetch_assoc()): ?>
                    <div class="comment-item d-flex align-items-start mb-4">
                        <img src="default-profile.png" alt="User Profile" class="rounded-circle me-3" width="50">
                        <div>
                            <strong><?php echo htmlspecialchars($comment['name']); ?></strong>
                            <p><?php echo htmlspecialchars($comment['content']); ?></p>
                            <small class="text-muted"><?php echo date("F j, Y, g:i a", strtotime($comment['datePosted'])); ?></small>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No comments yet. Be the first to comment!</p>
            <?php endif; ?>
        </div>

        <!-- Add Comment -->
        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']): ?>
            <div class="comment-form" style="background-color:rgb(255, 240, 249); border-radius: 8px; padding: 15px;">
                <form method="POST">
                    <div class="d-flex align-items-start mb-3">
                        <img src="user-profile.jpg" alt="Your Profile" class="rounded-circle me-3" width="50">
                        <textarea name="comment_content" class="form-control" placeholder="Leave a comment" style="background-color: #fafafa; border-color: #ccc;" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Post</button>
                </form>
            </div>
        <?php else: ?>
            <p><a href="login.php">Log in</a> to leave a comment.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const bookmarkButtons = document.querySelectorAll('.bookmark-btn');

    bookmarkButtons.forEach(button => {
        button.addEventListener('click', function () {
            <?php if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']): ?>
                // Show login prompt modal
                const loginModal = new bootstrap.Modal(document.getElementById('loginPromptModal'));
                loginModal.show();
            <?php else: ?>
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
            <?php endif; ?>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const bookmarkButtons = document.querySelectorAll('.bookmark-btn');

    bookmarkButtons.forEach(button => {
        button.addEventListener('click', function () {
            const folktalesID = this.dataset.folktalesId;
            const userID = this.dataset.userId;

            if (!userID) {
                // Show login prompt modal if user is not logged in
                const loginModal = new bootstrap.Modal(document.getElementById('loginPromptModal'));
                loginModal.show();
                return;
            }

            const icon = this.querySelector('i');
            const bookmarkCount = this.querySelector('.bookmark-count');
            const isBookmarked = icon.classList.contains('bi-bookmark-star-fill');

            // Send AJAX request to toggle bookmark
            fetch('toggle_bookmark.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ folktalesID, userID, action: isBookmarked ? 'remove' : 'add' })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Toggle the bookmark icon
                    if (isBookmarked) {
                        icon.classList.remove('bi-bookmark-star-fill');
                        icon.classList.add('bi-bookmark-star');
                    } else {
                        icon.classList.remove('bi-bookmark-star');
                        icon.classList.add('bi-bookmark-star-fill');
                    }

                    // Update the bookmark count
                    bookmarkCount.textContent = data.totalBookmarks;

                    // Show a notification
                    const modalEl = document.getElementById('bookmarkModal');
                    const modalMessage = document.getElementById('bookmarkMessage');
                    const modal = new bootstrap.Modal(modalEl);
                    modalMessage.textContent = isBookmarked ? 'Folktale removed from bookmarks!' : 'Folktale added to bookmarks!';
                    modal.show();
                } else {
                    alert('Failed to update bookmark: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the bookmark.');
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
