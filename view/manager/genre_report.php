<?php
session_start();

// Database connection
include '../dbconn.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';

$totalResult = "SELECT COUNT(DISTINCT genre) AS total FROM folktales";
if (!empty($search)) {
    $totalResult .= " WHERE genre LIKE '%" . $conn->real_escape_string($search) . "%'";
} 
$totalRecordsResult = mysqli_query($conn, $totalResult);
$totalRecords = mysqli_fetch_assoc($totalRecordsResult)['total'];

$limit = 10; 
$page = isset($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1; 
$totalPages = ceil($totalRecords / $limit); 
$page = min($page, $totalPages); 
$start = ($page - 1) * $limit; 

// Fetch paginated folktale data
$sql = "SELECT genre, COUNT(folktaleID) AS total_folktales FROM folktales";
if (!empty($search)) {
    $sql .= " WHERE genre LIKE '%" . $conn->real_escape_string($search) . "%'";
} 
$sql .= " GROUP BY genre ORDER BY total_folktales DESC LIMIT $start, $limit";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Folktales Genre List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Fredoka+One&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-image: linear-gradient(rgba(255, 255, 255, 0.7), rgba(247, 247, 247, 0.7)), url('../../assets/bg_2w.png');
            background-size: cover;
            background-position: center;
            height: 100vh;
            background-attachment: fixed;
            padding-top: 70px;
        }


        .container {
            margin-top: 20px;
        }

        h2 {
            font-family: 'Fredoka One', cursive;
            color: #32607C;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
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
            color: #fff;
        }

        .table th, .table td {
            padding: 15px;
            text-align: center;
        }

        .table tbody {
            background-color:rgb(255, 255, 255);
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
<?php include '../header.php'; ?>
 <!-- Content Section -->
<div id="content">
<div class="container mt-5">
    <h2 class="mb-4">Folktales's Genre List Report </h2>
    <form method="GET" action="" class="search-bar">
        <input type="text" name="search" placeholder="Search folktales..." value="<?php echo htmlspecialchars($search); ?>">
        <input type="hidden" name="page" value="1"> 
        <button type="submit"><i class="bi bi-search"></i> Search</button>
        <?php if (!empty($search)) : ?>
            <a href="genre_report.php" class="clear-search">Clear Search</a>
        <?php endif; ?>
    </form>

    <div class="table-responsive table-container">
        <table class="table table-bordered table-striped">
            <thead class="thead">
                <tr>
                    <th style="width:10%">Bil</th>
                    <th style="text-align:left">Genres</th>
                    <th>Total</th>
                    <th style="width:20%">Generate Report</th>
                </tr>
            </thead>
            <tbody class="tbody" id="genre-table-body">
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    $bil = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td style='width:10%'>{$bil}</td>";
                        echo "<td style='text-align:left'>{$row['genre']}</td>";
                        echo "<td>{$row['total_folktales']}</td>";
                        echo "<td><a href='genre_report_pdf.php?genre={$row['genre']}' class='btn btn-primary btn-sm'>Download PDF</a></td>"; // Generate Report button
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    function filterTable() {
        const searchInput = document.getElementById('search-input').value.toLowerCase();
        const tableRows = document.querySelectorAll('#genre-table-body tr');

        tableRows.forEach(row => {
            const genre = row.cells[1].textContent.toLowerCase(); // Search in genre column
            if (genre.includes(searchInput)) {
                row.style.display = ''; // Show matching rows
            } else {
                row.style.display = 'none'; // Hide non-matching rows
            }
        });
    }
</script>
</body>
</html>
