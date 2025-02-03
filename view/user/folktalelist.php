<?php
session_start();
include '../dbconn.php';

$userID = isset($_SESSION['userID']) ? $_SESSION['userID'] : null;
$search = isset($_GET['search']) ? $_GET['search'] : '';

$totalRecordsQuery = "SELECT COUNT(*) AS total FROM folktales";
if (!empty($search)) {
    $totalRecordsQuery .= " WHERE (title LIKE '%" . $conn->real_escape_string($search) . "%' 
                 OR genre LIKE '%" . $conn->real_escape_string($search) . "%' 
                 OR state LIKE '%" . $conn->real_escape_string($search) . "%')";
}
$totalRecordsResult = mysqli_query($conn, $totalRecordsQuery);
$totalRecords = mysqli_fetch_assoc($totalRecordsResult)['total'];

// Pagination logic
$recordsPerPage = 10;
$totalPages = ceil($totalRecords / $recordsPerPage);
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $recordsPerPage;

$search = isset($_GET['search']) ? $_GET['search'] : '';

$query = "SELECT * FROM folktales";
if (!empty($search)) {
    $query .= " WHERE (title LIKE '%" . $conn->real_escape_string($search) . "%' 
                 OR genre LIKE '%" . $conn->real_escape_string($search) . "%' 
                 OR state LIKE '%" . $conn->real_escape_string($search) . "%')";
}
$query .= " LIMIT $start, $recordsPerPage";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Folktales List</title>

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
            height: 100vh;
            color: #fff;
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
            background-color:rgb(255, 251, 251);
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
    <div class="d-flex">
        <?php if ($userID): ?>
            <?php include '../header.php'; ?>
        <?php else: ?>
            <?php include '../header_guest.php'; ?>
        <?php endif; ?>
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

            <!-- Search Form with Filters -->
            <form method="GET" action="" class="search-bar">
                <input type="text" name="search" placeholder="Search for folktales..." value="<?php echo htmlspecialchars($search); ?>">
                <input type="hidden" name="page" value="1"> 
                <button type="submit"><i class="bi bi-search"></i> Search</button>
                <?php if (!empty($search)) : ?>
                    <a href="folktalelist.php" class="clear-search">Clear Search</a>
                <?php endif; ?>
            </form>

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
                    <tbody class="tbody" id="bookmarks-table-body">
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

    <!-- Bootstrap JS CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>