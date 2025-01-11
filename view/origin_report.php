<?php
session_start();

// Database connection
include 'dbconn.php';

$totalResult = $conn->query("SELECT COUNT(DISTINCT state) AS total FROM folktales");
$totalRow = $totalResult->fetch_assoc();
$totalRecords = $totalRow['total'];

// Pagination setup
$limit = 10; // Number of records per page
$page = isset($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1; // Ensure valid page number
$totalPages = ceil($totalRecords / $limit); // Calculate total pages
$page = min($page, $totalPages); // Adjust if page exceeds total pages
$start = ($page - 1) * $limit; // Calculate the offset for the SQL query

// Fetch paginated folktale data
$sql = "SELECT state AS origin, COUNT(folktaleID) AS total_folktales FROM folktales GROUP BY state ORDER BY total_folktales DESC LIMIT $start, $limit";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Folktales List</title>
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
<?php include 'sidebar.php'; ?> 
<div id="content">
<div class="container mt-5">
    <h2 class="mb-4">Folktales's Origin List Report </h2>

    <!-- Search Filters -->
    <div class="search-bar">
        <input type="text" id="search-input" class="form-control" placeholder="Search by folktales origin.." oninput="filterTable()">
    </div>

    <div class="table-responsive table-container">
        <table class="table table-bordered table-striped">
            <thead class="thead">
                <tr>
                    <th>Bil</th>
                    <th>Origin</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody id="folktales-table-body">
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    $bil = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>{$bil}</td>";
                        echo "<td>{$row['origin']}</td>";
                        echo "<td>{$row['total_folktales']}</td>";
                        echo "</tr>";
                        $bil++;
                    }
                } else {
                    echo "<tr><td colspan='3' class='text-center'>No data found</td></tr>";
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
        const tableRows = document.querySelectorAll('#folktales-table-body tr');

        tableRows.forEach(row => {
            const origin = row.cells[1].textContent.toLowerCase(); // Search in "Origin" column
            if (origin.includes(searchInput)) {
                row.style.display = ''; // Show matching rows
            } else {
                row.style.display = 'none'; // Hide non-matching rows
            }
        });
    }
</script>
</body>
</html>
