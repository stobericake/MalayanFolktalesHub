<?php 
session_start();

// Database connection
include '../dbconn.php';

// Capture the search term if provided
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Get total records with search term consideration
$totalRecordsQuery = "SELECT COUNT(*) as total FROM folktales WHERE title LIKE ? OR state LIKE ? OR genre LIKE ?";
$stmt = $conn->prepare($totalRecordsQuery);
$searchTermLike = "%" . $searchTerm . "%";
$stmt->bind_param("sss", $searchTermLike, $searchTermLike, $searchTermLike);
$stmt->execute();
$totalRecordsResult = $stmt->get_result();
$totalRecords = $totalRecordsResult->fetch_assoc()['total'];

// Pagination setup
$limit = 10; // Number of records per page
$page = isset($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1; // Ensure valid page number
$totalPages = ceil($totalRecords / $limit); // Calculate total pages
$page = min($page, $totalPages); // Adjust if page exceeds total pages
$page = max($page, 1); // Ensure page is at least 1
$start = ($page - 1) * $limit; // Calculate the offset for the SQL query

// Fetch paginated folktale data with search term
$sql = "SELECT * FROM folktales WHERE title LIKE ? OR state LIKE ? OR genre LIKE ? LIMIT $start, $limit";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $searchTermLike, $searchTermLike, $searchTermLike);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Folktales Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body { 
            padding-top:70px;
        }

        /* Custom styles */
        thead th {
            background-color: #e3f2fd;
            color: #000;
        }

        tbody tr:hover {
            background-color: #f1f1f1;
        }

        .action-icons i {
            cursor: pointer;
        }

        .action-icons i:hover {
            color: #007bff; /* Bootstrap primary color */
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            width: 250px;
            padding: 10px;
            padding-right: 40px;
            border-radius: 25px;
            border: 1px solid #ced4da;
        }

        .search-box button {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #007bff;
            font-size: 18px;
            cursor: pointer;
        }

        .search-box button:hover {
            color: #0056b3;
        }
        
        /* Responsive search box */
        @media (max-width: 768px) {
            .search-box input {
                width: 200px;
            }
        }
    </style>
</head>
<body>
<?php include '../header.php'; ?>
<div id="content">
    <div class="container mt-3">
        <h1 class="mb-2">Folktales Management</h1>
        <div class="d-flex align-items-center mb-3">
            <a class="btn btn-success btn-sm" href="addfolktales.php">+ New</a>
            <div class="ms-auto">
                <div class="search-box">
                    <input type="text" id="searchInput" value="<?php echo htmlspecialchars($searchTerm); ?>" placeholder="Search folktales" oninput="filterTable()">
                    <button onclick="filterTable()"><i class="bi bi-search"></i></button>
                </div>
            </div>
        </div>
        <p>Showing <strong><?php echo $start + 1; ?></strong> to <strong><?php echo min($start + $limit, $totalRecords); ?></strong> of <strong><?php echo $totalRecords; ?></strong> items.</p>
        
        <div class="table-responsive table-container">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th></th>
                        <th>Title</th>
                        <th>State</th>
                        <th>Genre</th>
                    </tr>
                </thead>
                <tbody id="bookmarks-table-body">
<?php
if ($result && $result->num_rows > 0) {
    $bil = $start + 1; // Serial number starts from the current page start
    while ($row = $result->fetch_assoc()) {
        $editLink = "editfolktales.php?id=" . $row['folktaleID']; // Edit URL
        echo "<tr>
        <td class='action-icons text-center align-middle' style='width: 80px;' >
                <a href='$editLink' title='Edit'>
                    <i class='bi bi-pen me-3'></i>
                </a>
                <a href='#' title='Delete' onclick='deleteFolktale(\"{$row['folktaleID']}\")'>
                    <i class='bi bi-trash3'></i>
                </a>
            </td>
            <td>{$row['title']}</td>
            <td>{$row['state']}</td>
            <td>{$row['genre']}</td>
        </tr>";
        $bil++;
    }
} else {
    echo "<tr><td colspan='5' class='text-center'>No folktales found or unable to fetch data</td></tr>";
}
?>
                </tbody>
            </table>
        </div>

        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($searchTerm); ?>">Previous</a>
                </li>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($searchTerm); ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($searchTerm); ?>">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</div>

<script>
    function filterTable() {
        const input = document.getElementById("searchInput").value.toLowerCase();
        const rows = document.querySelectorAll("#bookmarks-table-body tr");
        
        // Update the URL for pagination purposes with the search term
        const url = new URL(window.location.href);
        url.searchParams.set("search", input);  // Update the search query parameter in URL
        history.pushState(null, "", url);  // Change URL without reloading the page
        
        rows.forEach(row => {
            const title = row.cells[1].textContent.toLowerCase();
            const state = row.cells[2].textContent.toLowerCase();
            const genre = row.cells[3].textContent.toLowerCase();
            row.style.display = (title.includes(input) || state.includes(input) || genre.includes(input)) ? "" : "none";
        });
    }
</script>

<script>
    function deleteFolktale(folktaleID) {
        if (confirm("Are you sure you want to delete this folktale?")) {
            // Send AJAX request to delete the folktale
            fetch(`delete_folktale.php?id=${folktaleID}`, { method: "GET" })
                .then(response => response.text())
                .then(data => {
                    if (data.trim() === "success") {
                        alert("Folktale deleted successfully!");
                        location.reload(); // Reload the page to refresh the table
                    } else {
                        alert("Error deleting folktale: " + data);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("An error occurred while trying to delete the folktale.");
                });
        }
    }
</script>

</body>
</html>
