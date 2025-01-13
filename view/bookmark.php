<?php
session_start();

// Database connection
include 'dbconn.php';

// Fetch total bookmarks for the logged-in user
$userID = 12;  // Set the logged-in user's ID dynamically based on session (e.g., $_SESSION['userID'])
$totalResult = $conn->query("SELECT COUNT(*) AS total FROM bookmark_folktale bf
                             JOIN bookmark b ON b.bookmarkID = bf.bookmarkID
                             WHERE b.userID = $userID");
$totalRow = $totalResult->fetch_assoc();
$totalBookmarks = $totalRow['total']; // This is the total number of bookmarks the user has added

// Pagination setup
$limit = 10; // Number of records per page
$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1; // Ensure valid page number
$totalPages = ceil($totalBookmarks / $limit); // Calculate total pages
$page = min($page, $totalPages); // Adjust if page exceeds total pages
$start = ($page - 1) * $limit; // Calculate the offset for the SQL query

// Fetch bookmarked folktales for the user
$sql = "SELECT f.title, f.genre, f.state, bf.count, bf.folktalesID
        FROM bookmark b
        JOIN bookmark_folktale bf ON b.bookmarkID = bf.bookmarkID
        JOIN folktales f ON bf.folktalesID = f.folktaleID
        WHERE b.userID = ?
        LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $userID, $start, $limit);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookmarks</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .total-bookmarks-card {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }
        .total-bookmarks-card span {
            font-size: 1.8rem;
            font-weight: bold;
            color: #5e72e4;
        }
        .table-container {
            margin-top: 20px;
        }
        .pagination {
            justify-content: center;
        }
        .search-bar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }
        .search-bar input {
            width: 50%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .search-bar input::placeholder {
            color: #6c757d;
            font-size: 16px;
            opacity: 1;
        }

        .thead th {
            background-color: #C3DFE6;
            color: black;
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?> 
<div id="content">
<div class="container mt-5">
    <h2 class="mb-4">Bookmarks</h2>

    <!-- Total Bookmarks Card -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="total-bookmarks-card">
                📚 <br>
                Total Bookmarks: <br>
                <span><?php echo $totalBookmarks; ?></span> 
            </div>
        </div>
    </div>

    <!-- Search Filters -->
    <div class="search-bar">
        <input type="text" id="search-input" class="form-control" placeholder="Search by title..." oninput="filterTable()">
    </div>

    <!-- Bookmarks Table -->
    <div class="table-responsive table-container">
        <table class="table table-bordered table-striped">
            <thead class="thead">
                <tr>
                    <th>Bil</th>
                    <th>Title</th>
                    <th>State</th>
                    <th>Genre</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="bookmarks-table-body">
<?php
if ($result->num_rows > 0) {
    $bil = $start + 1;
    while ($row = $result->fetch_assoc()) {
        echo "<tr data-folktalesid='" . $row['folktalesID'] . "'>";
        echo "<td>" . $bil++ . "</td>";
        echo "<td>" . htmlspecialchars($row['title']) . "</td>";
        echo "<td>" . htmlspecialchars($row['state']) . "</td>";
        echo "<td>" . htmlspecialchars($row['genre']) . "</td>";
        echo "<td>
            <button class='btn btn-danger btn-sm delete-btn' data-folktales-id='" . $row['folktalesID'] . "'>
            Delete</button>
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

    <!-- Pagination -->
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $page - 1; ?>">Previous</a>
            </li>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
            <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $page + 1; ?>">Next</a>
            </li>
        </ul>
    </nav>
</div>
</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    // Filter table by title
    function filterTable() {
        const searchInput = document.getElementById('search-input').value.toLowerCase();
        const tableRows = document.querySelectorAll('#bookmarks-table-body tr');

        tableRows.forEach(row => {
            const title = row.cells[1].textContent.toLowerCase();
            if (title.includes(searchInput)) {
                row.style.display = ''; // Show matching rows
            } else {
                row.style.display = 'none'; // Hide non-matching rows
            }
        });
    }

    // Handle bookmark deletion
    let folktalesIDToDelete = null;
    let userIDToDelete = 12; // Assume the logged-in user ID is 12 (adjust dynamically)

    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            folktalesIDToDelete = this.getAttribute('data-folktales-id');
            const modal = new bootstrap.Modal(document.getElementById('bookmarkModal'));
            modal.show();
        });
    });

    // Confirm delete action
document.getElementById('confirm-delete-btn').addEventListener('click', function() {
    if (folktalesIDToDelete !== null && userIDToDelete !== null) {
        fetch('delete_bookmark.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                folktalesID: folktalesIDToDelete,
                userID: userIDToDelete
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Close the modal
                var myModal = bootstrap.Modal.getInstance(document.getElementById('bookmarkModal'));
                myModal.hide();

                // Optionally remove the row from the table
                let row = document.querySelector(`tr[data-folktalesid="${folktalesIDToDelete}"]`);
                if (row) row.remove();

                // Update total bookmarks count
                const totalBookmarksElement = document.querySelector('.total-bookmarks-card span');
                const currentCount = parseInt(totalBookmarksElement.textContent);
                if (!isNaN(currentCount) && currentCount > 0) {
                    totalBookmarksElement.textContent = currentCount - 1;
                }
            } else {
                alert(data.message);
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
