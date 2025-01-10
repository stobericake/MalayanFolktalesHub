<?php
session_start();

// Database connection
include 'dbconn.php';


// Get total records
$totalRecordsQuery = "SELECT COUNT(*) as total FROM folktales";
$totalRecordsResult = $conn->query($totalRecordsQuery);
$totalRecords = $totalRecordsResult->fetch_assoc()['total'];

// Pagination setup
$limit = 10; // Number of records per page
$page = isset($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1; // Ensure valid page number
$totalPages = ceil($totalRecords / $limit); // Calculate total pages
$page = min($page, $totalPages); // Adjust if page exceeds total pages
$page = max($page, 1); // Ensure page is at least 1
$start = ($page - 1) * $limit; // Calculate the offset for the SQL query

// Fetch paginated folktale data
$sql = "SELECT * FROM folktales LIMIT $start, $limit";
$result = $conn->query($sql);
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
            margin-right: 8px;
        }
        .action-icons i:hover {
            color: #007bff; /* Bootstrap primary color */
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?> 
<div id="content">
    <div class="container mt-3">
        <h1 class="mb-2">Folktales Management</h1>
        <div class="d-flex align-items-center mb-3">
            <a class="btn btn-success btn-sm" href="addfolktales.php">+ New</a>
            <div class="ms-auto">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Search folktales" oninput="filterTable()">
                    <button onclick="filterTable()"><i class="bi bi-search"></i></button>
                </div>
            </div>
        </div>
        <p>Showing <strong><?php echo $start + 1; ?></strong> to <strong><?php echo min($start + $limit, $totalRecords); ?></strong> of <strong><?php echo $totalRecords; ?></strong> items.</p>
        
        <div class="table-responsive table-container">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Bil</th>
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
        $deleteLink = "deleteFolktale.php?id=" . $row['folktaleID']; // Delete URL
        echo "<tr>
                <td>{$bil}</td>
                <td class='action-icons'>
                    <a href='{$editLink}' title='Edit'>
                        <i class='bi bi-pen'></i>
                    </a>
                    <a href='{$deleteLink}' title='Delete' onclick='return confirm(\"Are you sure you want to delete this folktale?\")'>
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

<script>
    function filterTable() {
        const input = document.getElementById("searchInput").value.toLowerCase();
        const rows = document.querySelectorAll("#bookmarks-table-body tr");
        rows.forEach(row => {
            const title = row.cells[2].textContent.toLowerCase();
            const state = row.cells[3].textContent.toLowerCase();
            const genre = row.cells[4].textContent.toLowerCase();
            row.style.display = (title.includes(input) || state.includes(input) || genre.includes(input)) ? "" : "none";
        });
    }
</script>
</body>
</html>
