<?php
session_start();
include '../dbconn.php';

$userID = isset($_SESSION['userID']) ? $_SESSION['userID'] : null;

$search = isset($_GET['search']) ? $_GET['search'] : '';

$totalQuery = "SELECT COUNT(*) AS total 
            FROM bookmark b
            JOIN bookmark_folktale bf ON b.bookmark_folktaleID = bf.bookmark_folktaleID
            JOIN folktales f ON bf.folktaleID = f.folktaleID
            WHERE b.userID = ?";
if (!empty($search)) {
    $totalQuery .= " AND (f.title LIKE '%" . $conn->real_escape_string($search) . "%' 
                 OR f.genre LIKE '%" . $conn->real_escape_string($search) . "%' 
                 OR f.state LIKE '%" . $conn->real_escape_string($search) . "%')";
    }
$totalStmt = $conn->prepare($totalQuery);
$totalStmt->bind_param("i", $userID);
$totalStmt->execute();
$totalResult = $totalStmt->get_result();
$totalRow = $totalResult->fetch_assoc();
$totalBookmarks = $totalRow['total']; 


$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$totalPages = ceil($totalBookmarks / $limit); 
$page = min($page, $totalPages); 
$start = ($page - 1) * $limit;


// Fetch bookmarked folktales for the user
$sql = "SELECT f.title, f.genre, f.state, bf.count, bf.folktaleID
        FROM bookmark b
        JOIN bookmark_folktale bf ON b.bookmark_folktaleID = bf.bookmark_folktaleID
        JOIN folktales f ON bf.folktaleID = f.folktaleID
        WHERE b.userID = ?";
if (!empty($search)) {
    $sql .= " AND (f.title LIKE '%" . $conn->real_escape_string($search) . "%' 
                 OR f.genre LIKE '%" . $conn->real_escape_string($search) . "%' 
                 OR f.state LIKE '%" . $conn->real_escape_string($search) . "%')";
    }
$sql .= " LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $userID, $start, $limit);
$stmt->execute();
$result = $stmt->get_result();

// Close the database connection
$stmt->close();
$totalStmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookmarks</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Fredoka+One&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-image: linear-gradient(rgba(255, 255, 255, 0.7), rgba(247, 247, 247, 0.7)), url('../../assets/bg_2w.png');
            background-size: cover;
            background-position: center;
            height: 100vh;
            background-attachment: fixed;
            padding-top: 100px;
        }

        .container {
            margin-top: 20px;
            margin-bottom: 50px;
        }

        h2 {
            font-family: 'Fredoka One', cursive;
            color: #32607C;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .total-bookmarks-card {
            background-color: #32607C;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            font-size: 18px;
            color:white;
        }
        .total-bookmarks-card span {
            font-size: 24px;
            font-weight: bold;
        }
        
        .search-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        .search-bar input {
            flex: 1;
            border-radius: 25px;
            padding: 10px 20px;
            border: 2px solid #32607C;
            font-size: 16px;
        }
        .search-bar button {
            padding: 10px 20px;
            border: none;
            background-color: #32607C;
            color: white;
            cursor: pointer;
            border-radius: 25px;
            transition: background-color 0.3s ease;
        }
        .search-bar button:hover {
            background-color: #234956;
        }
        .clear-search {
            color: #32607C;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }
        .clear-search:hover {
            color: #234956;
        }

        .table-responsive {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .table thead {
            background-color: #32607C;
            color: white !important;
        }

        .table th {
            padding: 15px;
            text-align: center;
            color: white !important;
        }
        .table td {
            padding: 15px;
            text-align: center;
            color: black !important;
        }

        .table tbody {
            background-color:rgb(255, 255, 255);
        }

        .btn-primary {
            background-color:rgb(138, 194, 228);
            border: none;
            color: black;
            text-decoration: none;
            border-radius: 25px;
            padding: 5px 10px;
            font-size: 20px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #234956;
            transform: scale(1.05);
        }

        .btn-remove {
            background-color:rgb(228, 138, 138);
            border: none;
            color: black;
            text-decoration: none;
            border-radius: 25px;
            padding: 5px 10px;
            font-size: 20px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn-remove:hover {
            background-color:rgb(86, 35, 35);
            transform: scale(1.05);
        }
        
        .pagination {
            justify-content: center;
            margin-top: 20px;
            margin-bottom: 80px;
        }

        .page-item.active .page-link {
            background-color: #32607C;
            border-color: #32607C;
        }

        .page-link {
            color: #32607C;
            border-radius: 25px;
            margin: 0 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .page-link:hover {
            background-color: #32607C;
            color: #fff;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .table tbody tr {
            animation: slideIn 0.5s ease-out;
        }
    </style>
</head>
<body>
<div class="d-flex">
<?php include '../header.php'; ?> 
<div class="container">
    <h2 class="mb-4">Bookmarks</h2>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="total-bookmarks-card">
                📚 <br>
                Total Bookmarks: <br>
                <span><?php echo $totalBookmarks; ?></span> 
            </div>
        </div>
    </div>

    <form method="GET" action="" class="search-bar">
        <input type="text" name="search" placeholder="Enter title / genre / origin" value="<?php echo htmlspecialchars($search); ?>">
        <input type="hidden" name="page" value="1"> 
        <button type="submit"><i class="bi bi-search"></i> Search</button>
        <?php if (!empty($search)) : ?>
            <a href="bookmark.php" class="clear-search">Clear Search</a>
        <?php endif; ?>
    </form>

    <!-- Bookmarks Table -->
    <div class="table-responsive table-container">
        <table class="table table-bordered table-striped">
            <thead class="thead">
                <tr>
                    <th>Bil</th>
                    <th style="text-align: start">Title</th>
                    <th>Origin</th>
                    <th>Genre</th>
                    <th style="width: 15%">View detail / Remove</th>
                </tr>
            </thead>
            <tbody class="tbody" id="bookmarks-table-body">
            <?php
            if ($result->num_rows > 0) {
                $bil = $start + 1;
                while ($row = $result->fetch_assoc()) {
                    echo "<tr data-folktaleID='" . $row['folktaleID'] . "'>";
                    echo "<td>" . $bil++ . "</td>";
                    echo "<td style='text-align: start'>" . htmlspecialchars($row['title']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['state']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['genre']) . "</td>";
                    echo "<td>
                            <button class='btn-primary btn-sm mb-1' onclick=\"window.location.href='folktaledetails.php?id=" . $row['folktaleID'] . "'\">
                                <i class='bi bi-eye'></i>
                            </button>
                            <button class='btn-remove btn-sm delete-btn mb-1' data-folktales-id='" . $row['folktaleID'] . "'>
                                <i class='bi bi-trash3'></i>
                            </button>
                        </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No records found</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
    
    <!-- Bookmark Delete Confirmation Modal -->
    <div class="modal fade" id="bookmarkModal" tabindex="-1" aria-labelledby="bookmarkModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookmarkModalLabel">Delete Bookmark</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this bookmark?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirm-delete-btn">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <nav aria-label="Page navigation">
            <ul class="pagination">
                <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>"><<</a>
                </li>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>">>></a>
                </li>
            </ul>
        </nav>
</div>
</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    // Handle bookmark deletion
    let folktaleIDToDelete = null;
    let userIDToDelete = <?php echo isset($_SESSION['userID']) ? $_SESSION['userID'] : 'null'; ?>; // Dynamically get userID from session

    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            folktaleIDToDelete = this.getAttribute('data-folktales-id');
            const modal = new bootstrap.Modal(document.getElementById('bookmarkModal'));
            modal.show();
        });
    });

    // Confirm delete action
    document.getElementById('confirm-delete-btn').addEventListener('click', function() {
        if (folktaleIDToDelete !== null && userIDToDelete !== null) {
            fetch('delete_bookmark.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    folktaleID: folktaleIDToDelete,
                    userID: userIDToDelete
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close the modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('bookmarkModal'));
                    modal.hide();

                    // Remove the row from the table
                    const row = document.querySelector(`tr[data-folktaleID="${folktaleIDToDelete}"]`);
                    if (row) row.remove();

                    // Update total bookmarks count
                    const totalBookmarksElement = document.querySelector('.total-bookmarks-card span');
                    const currentCount = parseInt(totalBookmarksElement.textContent);
                    if (!isNaN(currentCount) && currentCount > 0) {
                        totalBookmarksElement.textContent = currentCount - 1;
                    }

                    // Show success message
                    alert('Bookmark deleted successfully.');
                } else {
                    alert(data.message || 'Failed to delete bookmark.');
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("An error occurred while deleting the bookmark.");
            });
        }
    });
</script>
</body>
</html>
