<?php
// Database connection
include '../dbconn.php';

// Fetch total number of folktales
$totalRecordsQuery = "SELECT COUNT(*) AS total FROM folktales";
$totalRecordsResult = mysqli_query($conn, $totalRecordsQuery);
$totalRecords = mysqli_fetch_assoc($totalRecordsResult)['total'];

// Pagination logic
$recordsPerPage = 10;
$totalPages = ceil($totalRecords / $recordsPerPage);
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $recordsPerPage;

// Fetch folktales with pagination
$query = "SELECT * FROM folktales LIMIT $start, $recordsPerPage";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Folktales List</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Fredoka+One&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            padding-top: 100px;
        }

        .container {
            margin-top: 20px;
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
            margin-bottom: 20px;
        }

        .search-bar input {
            border-radius: 25px;
            padding: 10px 20px;
            border: 2px solid #32607C;
            font-size: 16px;
        }

        .table-responsive {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .table thead {
            background-color: #32607C;
            color: #fff;
        }

        .table th, .table td {
            padding: 15px;
            text-align: center;
        }

        .table tbody tr:hover {
            background-color: #f0f0f0;
        }

        .btn-primary {
            background-color: #32607C;
            border: none;
            border-radius: 25px;
            padding: 8px 20px;
            font-size: 14px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #234956;
            transform: scale(1.05);
        }

        .pagination {
            justify-content: center;
            margin-top: 20px;
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
    </style>
</head>
<body>
    <div class="d-flex">
        <?php include '../header.php'; ?>
        <div class="container">
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

            <!-- Table -->
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
                            echo "<tr><td colspan='5'>No folktales found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $page - 1; ?>"><<</a>
                    </li>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $page + 1; ?>">>></a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <!-- Bootstrap JS CDN -->
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