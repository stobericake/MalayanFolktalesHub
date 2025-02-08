<?php
session_start();
include '../dbconn.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';

$totalResult = "SELECT COUNT(*) AS total FROM user";
if (!empty($search)) {
    $totalResult .= " WHERE name LIKE '%" . $conn->real_escape_string($search) . "%'";
} 
$totalRecordsResult = mysqli_query($conn, $totalResult);
$totalRecords = mysqli_fetch_assoc($totalRecordsResult)['total'];

$limit = 10; 
$page = isset($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1; 
$totalPages = ceil($totalRecords / $limit); 
$page = min($page, $totalPages); 
$start = ($page - 1) * $limit; 
$search = isset($_GET['search']) ? $_GET['search'] : '';


$query = "SELECT u.userID, u.name, u.email, 
                 COALESCE((SELECT COUNT(*) 
                           FROM bookmark b 
                           JOIN bookmark_folktale bf ON b.bookmark_folktaleID = bf.bookmark_folktaleID 
                           WHERE b.userID = u.userID), 0) AS total_bookmarks, 
                 COALESCE(COUNT(c.commentID), 0) AS total_comments 
          FROM user u 
          LEFT JOIN comment c ON u.userID = c.userID";
if (!empty($search)) {
    $query .= " WHERE  u.name LIKE '%" . $conn->real_escape_string($search) . "%'";
} 
$query .= " GROUP BY u.userID 
          ORDER BY u.name ASC 
          LIMIT $start, $limit";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Fredoka+One&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-image: linear-gradient(rgba(255, 255, 255, 0.7), rgba(247, 247, 247, 0.7)), url('../../assets/bg_2w.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            padding-top: 100px;
        }

        .container {
            margin-top: 20px;
            margin-bottom: 70px;
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

        .download-button {
            align-items: end;
            padding: 10px 20px;
            border: none;
            background-color: #32607C;
            color: white;
            cursor: pointer;
            border-radius: 25px;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }
        .download-button :hover {
            background-color: #234956;
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
            background-color:rgb(255, 254, 254);
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
    <div class="container">
        <h2 class="mb-4">User Report</h2>
        <form method="GET" action="" class="search-bar">
            <input type="text" name="search" placeholder="Search user..." value="<?php echo htmlspecialchars($search); ?>">
            <input type="hidden" name="page" value="1"> 
            <button type="submit"><i class="bi bi-search"></i> Search</button>
            <?php if (!empty($search)) : ?>
                <a href="user_report.php" class="clear-search">Clear Search</a>
            <?php endif; ?>
        </form>

        <a type="submit" class="download-button mb-3" style="font-size: 16px" href="all_user_pdf.php">
            <i class='bi bi-file-earmark-arrow-down'></i> Download all user report
        </a>
        <div class="table-responsive table-container">
            <table class="table table-bordered table-striped">
                <thead class="thead">
                    <tr>
                    <th style="width:10%">Bil</th>
                        <th style="text-align:left">Name</th>
                        <th>Email</th>
                        <th>Total Bookmarks</th>
                        <th>Total Comments</th>
                        <th style="width:15%">Download Report</th>
                    </tr>
                </thead>
                <tbody class="tbody" id="user-table-body">
                    <?php
                    if ($result && mysqli_num_rows($result) > 0) {
                        $bil = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>{$bil}</td>";
                            echo "<td style='text-align:left'>{$row['name']}</td>";
                            echo "<td>{$row['email']}</td>";
                            echo "<td>{$row['total_bookmarks']}</td>";
                            echo "<td>{$row['total_comments']}</td>";
                            echo "<td style='width: 20%'><a href='user_report_pdf.php?userID={$row['userID']}' class='btn-primary btn-sm'>
                            <i class='bi bi-file-earmark-arrow-down'></i></a>
                            </td>"; 
                            echo "</tr>";
                            $bil++;
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>No users found</td></tr>";
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        function filterTable() {
            const searchInput = document.getElementById('search-input').value.toLowerCase();
            const tableRows = document.querySelectorAll('#user-table-body tr');

            tableRows.forEach(row => {
                const name = row.cells[1].textContent.toLowerCase();
                const email = row.cells[2].textContent.toLowerCase();
                if (name.includes(searchInput) || email.includes(searchInput)) {
                    row.style.display = ''; 
                } else {
                    row.style.display = 'none'; 
                }
            });
        }
    </script>
</body>
</html>
