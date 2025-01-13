<?php
session_start();

// Database connection
include 'dbconn.php';

// Fetch total records for bookmarks count
$totalResult = $conn->query("SELECT COUNT(*) AS total FROM folktales");
$totalRow = $totalResult->fetch_assoc();
$totalRecords = $totalRow['total'];

// Pagination setup
$limit = 10; // Number of records per page
$page = isset($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1; // Ensure valid page number
$totalPages = ceil($totalRecords / $limit); // Calculate total pages
$page = min($page, $totalPages); // Adjust if page exceeds total pages
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
    <title>Folktales List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
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
            color: #6c757d; /* A visible color */
            font-size: 16px; /* Adjust font size if necessary */
            opacity: 1; /* Ensure full visibility */
        }
        .thead th {
            background-color: #C3DFE6;
            color: black;
        }
        .view-details-column {
        width: 1%; /* Shrink-wrap to content */
        white-space: nowrap; /* Prevent wrapping */
    }
    </style>
</head>
<body>
<div class="d-flex">
<?php include 'sidebar.php'; ?> 

<div class="container-fluid">
    <h2 class="mb-4">Folktales List</h2>

   <div class="row mb-4">
        <div class="col-md-4">
            <div class="total-bookmarks-card">
                📖 <br>
                Total Folktales: <br>
                <span><?php echo $totalRecords; ?></span>
            </div>
        </div>
    </div>

    <!-- Search Filters -->
    <div class="search-bar">
    <input type="text" id="search-input" class="form-control" placeholder="Search folktales details.." oninput="filterTable()">
</div>
    <div class="table-responsive table-container">
        <table class="table table-bordered table-striped">
            <thead class="thead">
                <tr>
                    <th>Bil</th>
                    <th>Title</th>
                    <th>State</th>
                    <th>Genre</th>
                    <th class="view-details-column"></th>
                </tr>
            </thead>
            <tbody id="bookmarks-table-body">
                <?php
                if ($result->num_rows > 0) {
                    $bil = $start + 1; // Serial number starts from the offset + 1
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$bil}</td>
                                <td>{$row['title']}</td>
                                <td>{$row['state']}</td>
                                <td>{$row['genre']}</td>
                                <td class='view-details-column'>
                                    <a href='folktaledetails.php?id={$row['folktaleID']}' class='btn btn-primary btn-sm'>View Details</a>
                                </td>
                              </tr>";
                        $bil++; // Increment serial number
                    }
                } else {
                    echo "<tr><td colspan='4'>No folktales found</td></tr>";
                }
                ?>
            </tbody>
        </table>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    function filterTable() {
    const searchInput = document.getElementById('search-input').value.toLowerCase();
    const tableRows = document.querySelectorAll('#bookmarks-table-body tr');

    tableRows.forEach(row => {
        const title = row.cells[1].textContent.toLowerCase(); // Title column
        const state = row.cells[2].textContent.toLowerCase(); // State column
        const genre = row.cells[3].textContent.toLowerCase(); // Genre column

        // Show the row if any column matches the search input
        if (title.includes(searchInput) || state.includes(searchInput) || genre.includes(searchInput)) {
            row.style.display = ''; // Show matching rows
        } else {
            row.style.display = 'none'; // Hide non-matching rows
        }
    });
}

</script>
</body>
</html>
