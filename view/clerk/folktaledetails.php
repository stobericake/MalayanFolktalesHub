<?php
session_start();
include '../dbconn.php';

$folktaleID = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch folktale details from the database
$sql = "SELECT * FROM folktales WHERE folktaleID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $folktaleID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $folktale = $result->fetch_assoc();
    $pdfData = base64_encode($folktale['pdfPath']); 
} else {
    die("Folktale not found.");
}


$userID = isset($_SESSION['userID']) ? $_SESSION['userID'] : null;
// Fetch comments from the database
$commentsQuery = "SELECT c.commentID, c.content, c.datePosted, u.name, c.userID 
    FROM comment c 
    JOIN user u ON c.userID = u.userID 
    WHERE c.folktaleID = ?
    ORDER BY c.datePosted DESC";
$stmt = $conn->prepare($commentsQuery);
$stmt->bind_param("i", $folktaleID);
$stmt->execute();
$commentsResult = $stmt->get_result();

// Handle new comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_content'])) {
  $commentContent = trim($_POST['comment_content']);
  if (!empty($commentContent) && $userID) {
      $insertCommentQuery = "INSERT INTO comment (content, datePosted, userID, folktaleID) VALUES (?, NOW(), ?, ?)";
      $insertStmt = $conn->prepare($insertCommentQuery);
      $insertStmt->bind_param("sii", $commentContent, $userID, $folktaleID);
      if ($insertStmt->execute()) {
          header("Location: ?id=$folktaleID");
          exit;
      } else {
          echo "Error: " . $insertStmt->error;
      }
      $insertStmt->close();
  } else {
      echo "Comment content is empty or user is not logged in.";
  }
}

// Check if the current user has bookmarked this folktale
$isBookmarked = false;
if ($userID) {
    $checkBookmark = $conn->prepare("SELECT bookmarkID FROM bookmark WHERE userID = ?
                                      AND bookmark_folktaleID = ( 
                                      SELECT bookmark_folktaleID FROM bookmark_folktale 
                                      WHERE folktaleID = ?)");
    $checkBookmark->bind_param("ii", $userID, $folktaleID);
    $checkBookmark->execute();
    $checkBookmark->store_result();
    $isBookmarked = $checkBookmark->num_rows > 0;
    $checkBookmark->close();
}

$totalBookmarksStmt = $conn->prepare("SELECT count FROM bookmark_folktale WHERE folktaleID = ?");
$totalBookmarksStmt->bind_param("i", $folktaleID);
$totalBookmarksStmt->execute();
$totalBookmarksStmt->store_result();
$totalBookmarksStmt->bind_result($totalBookmarks); 
$totalBookmarksStmt->fetch(); 
$totalBookmarksStmt->close()
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Folktales Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Fredoka+One&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
  <style>
    body {
        background-image: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('../../assets/background.png');
        background-size: cover;
        background-position: center;
        background-attachment: fixed; 
        min-height: 100vh;
        font-family: 'Poppins', sans-serif;
        padding-top: 90px;
    }

    .container {
        background-color: rgba(255, 255, 255, 0); 
        border-radius: 15px;
        padding: 20px;
        margin-top: 20px;
        margin-bottom: 20px;
    }
    .folktale-card {
        background-color: rgba(68, 116, 156, 0.9);
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
        color: rgb(228, 232, 235);
        margin-bottom: 20px;
    }

    .folktale-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .folktale-details {
        flex: 1; 
    }

    .folktale-buttons {
        display: flex;
        flex-direction: column; 
        gap: 10px; 
        margin-left: 20px; 
    }

    .btn-folktale {
      background-color:rgb(184, 199, 243);
      color: black;
      border: none;
      border-radius: 10px;
      padding: 10px 20px;
      transition: background-color 0.3s ease;
      min-width: 230px;
    }

    .btn-comment {
      min-width: 100px;
      background-color:rgba(68, 116, 156, 0.9);
      color: white;
      border: none;
      padding: 8px 20px;
      transition: background-color 0.3s ease;
      border-radius: 10px;
    }

    .btn-signin {
      min-width: 100px;
      background-color:rgba(68, 116, 156, 0.9);
      color: white;
      border: none;
      padding: 8px 20px;
      transition: background-color 0.3s ease;
      border-radius: 10px;
    }

    .btn-custom:hover {
      background-color:rgb(129, 158, 248);
    }
    .comment-section {
        background-color: rgba(195, 223, 230, 0.9);
        padding: 15px;
        border-radius: 10px;
        margin-top: 20px;
        margin-bottom: 50px;
    }

    .comment {
        background-color: #fff;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: flex-start;
        gap: 10px;
        max-height: 200px; 
        overflow-y: auto; 
        margin-bottom: 15px;
        animation: fadeInRight 1s ease-in-out;
    }

    .comment .user-icon {
        font-size: 40px;
        color: #44749c; 
    }

    .comment .comment-content {
        flex: 1; 
    }

    .comment .comment-content .comment-meta {
        font-size: 0.9em;
        color: #666;
        margin-bottom: 5px;
    }

    .comment .comment-content .comment-meta strong {
        color: #333;
    }

    .comment .comment-content p {
        margin: 0;
    }

    textarea {
        width: 100%;
        padding: 10px;
        border-radius: 10px;
        border: 1px solid #ddd;
        margin-bottom: 10px;
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
    .delete-comment {
        margin-left: auto;
        cursor: pointer;
    }

    .delete-comment i {
        color: #ff4444;
        font-size: 1.2em;
    }

    .delete-comment i:hover {
        color: #cc0000;
    }
    @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
  </style>
</head>
<body>
<?php if ($userID): ?>
<?php include '../header.php'; ?>
<?php else: ?>
<?php include '../header_guest.php'; ?>
<?php endif; ?>
  <div class="container">
    <!-- Folktale Details -->
    <div class="folktale-card">
      <div class="folktale-header">
          <div class="folktale-details">
              <h1 class="text-left mb-4" style="color: white; font-family: 'Fredoka One', cursive;">📖 <?php echo htmlspecialchars($folktale['title']); ?></h1>
              <p>
                  <i class="bi bi-blockquote-left"> </i><?php echo htmlspecialchars($folktale['genre']); ?> |  
                  <i class="bi bi-globe-central-south-asia"></i> <?php echo htmlspecialchars($folktale['state']); ?> |  
                  <i class="bi bi-bookmarks"></i> <?php echo $totalBookmarks; ?> Bookmarks
              </p>
          </div>
          <div class="folktale-buttons">
              <button class="btn-custom btn-folktale bookmarkBtn" onclick="window.location.href = 'editfolktales.php?id=<?php echo $folktaleID; ?>'">
                <i class='bi bi-pencil-square'></i> Edit folktale
              </button>
              <button class="btn-custom btn-folktale delete-btn" title="Delete" data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal" data-id='<?php echo $folktaleID; ?>'>
                <i class='bi bi-trash3'></i> Delete folktale
              </button>
          </div>
      </div>
      <div>
          <p><i class="bi bi-card-text"></i> Synopsis:</p>   
          <div style="word-wrap: break-word; padding-left: 10px; padding-right: 15px">
              <?php echo nl2br(htmlspecialchars($folktale['synopsis'], ENT_QUOTES, 'UTF-8')); ?>
          </div>
      </div>
  </div>

  <div class="comment-section">
    <h3>💬 Comments</h3>
    <div id="comments" style="max-height: 320px; overflow-y: auto;">
        <?php while ($comment = $commentsResult->fetch_assoc()): ?>
            <div class="comment">
                <div class="user-icon">
                    <i class="bi bi-person-circle"></i> 
                </div>
                <!-- Comment content -->
                <div class="comment-content">
                    <div class="comment-meta">
                        <strong><?php echo htmlspecialchars($comment['name']); ?></strong> <!-- User name -->
                        <span>• <?php echo date('F j, Y', strtotime($comment['datePosted'])); ?></span> <!-- Formatted date -->
                    </div>
                    <p><?php echo htmlspecialchars($comment['content']); ?></p> <!-- Comment text -->
                </div>
                    <div class="delete-comment">
                        <i class="bi bi-trash" data-bs-toggle="modal" data-bs-target="#deleteModal" data-comment-id="<?php echo $comment['commentID']; ?>"></i>
                    </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Delete Folktale Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this folktale? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
 
  <script>
        function toggleBookmark(folktaleID) {
        console.log('Toggling bookmark for folktaleID:', folktaleID);

        fetch('toggle_bookmark.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'folktaleID=' + folktaleID
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.statusText);
            }
            return response.text();
        })
        .then(data => {
            console.log('Response from server:', data);
            const bookmarkBtn = document.getElementById('bookmarkBtn');
            if (data === 'added') {
                bookmarkBtn.innerHTML = '<i class="bi bi-bookmark-heart-fill"></i> Remove Bookmark';
            } else if (data === 'removed') {
                bookmarkBtn.innerHTML = '<i class="bi bi-bookmark-heart"></i> Add to Bookmark';
            }
        })

        .catch(error => {
            console.error('Error during fetch:', error);
        });
    }

    function confirmPost() {
        return confirm("Are you sure you want to post this comment?");
    }
    document.addEventListener('DOMContentLoaded', function () {
        var deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Button that triggered the modal
            var commentID = button.getAttribute('data-comment-id'); // Extract commentID from data attribute
            var confirmDeleteButton = deleteModal.querySelector('#confirmDeleteButton');
            confirmDeleteButton.setAttribute('onclick', `deleteComment(${commentID})`);
        });
    });
  </script>
  <script>
    let folktaleIDToDelete = null;
    document.querySelectorAll('[data-bs-target="#deleteConfirmationModal"]').forEach(button => {
        button.addEventListener('click', () => {
            folktaleIDToDelete = button.getAttribute('data-id');
        });
    });
    document.getElementById('confirmDelete').addEventListener('click', () => {
        if (folktaleIDToDelete) {
            fetch(`delete_folktale.php?id=${folktaleIDToDelete}`, { method: "GET" })
                .then(response => response.text())
                .then(data => {
                    if (data.trim() === "success") {
                        alert("Folktale deleted successfully!");
                        window.location.href = 'clerklisting.php';
                    } else {
                        alert("Error deleting folktale: " + data);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("An error occurred while trying to delete the folktale.");
                });
        }
        // Close the modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
        modal.hide();
    });
</script>

</body>
</html>