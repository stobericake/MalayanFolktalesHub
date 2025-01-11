<?php
session_start();

// Database connection
include 'dbconn.php';


// Fetch folktale data
$sql = "SELECT * FROM folktales";
$result = $conn->query($sql);

// Pagination setup
$limit = 10; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Fetch total records
$totalResult = $conn->query("SELECT COUNT(*) AS total FROM folktales");
$totalRow = $totalResult->fetch_assoc();
$totalRecords = $totalRow['total'];
$totalPages = ceil($totalRecords / $limit);

// Fetch paginated folktale data
$sql = "SELECT * FROM folktales LIMIT $start, $limit";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Generate Report</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/style.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f8f9fa;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .title {
      font-size: 24px;
      color: #007b5e;
    }
    .pagination {
            justify-content: center;
        }s

    .logout {
      background-color: #fff;
      border: 1px solid #ddd;
      border-radius: 4px;
      padding: 8px 12px;
      cursor: pointer;
    }

    .subtitle {
      font-size: 18px;
      margin-bottom: 15px;
    }
    .table-container {
            margin-top: 20px;
            margin-bottom: 20px;
        }
    .report-table {
      width: 100%;
      border-collapse: collapse;
      background-color: #fff;
      border: 1px solid #ddd;
    }

    .report-table th,
    .report-table td {
      padding: 10px;
      text-align: left;
      border: 1px solid #ddd;
    }
    
    .tag {
      display: inline-block;
      padding: 2px 8px;
      border-radius: 12px;
      color: #fff;
      font-size: 12px;
    }
    
    .tag.teladan {
      background-color: #28a745;
    }

    .tag.legenda {
      background-color:rgb(184, 160, 23);
    }

    .tag.jenaka {
      background-color: #ffc107;
    }

    .tag.fabel {
      background-color: #fd7e14;
    }

    .tag.alegori {
      background-color: #6f42c1;
    }

    .tag.moral {
      background-color:rgb(66, 74, 193);
    }

    .tag.sejarah {
      background-color:rgb(159, 193, 66);
    }

    .tag.ceritarakyat {
      background-color:rgb(66, 117, 193);
    }

    .tag.kanak-kanak {
      background-color:rgb(66, 142, 193);
    }

    .tag.pengembaraan {
      background-color:rgb(193, 66, 125);
    }

    .tag.dongeng {
      background-color:rgb(66, 110, 193);
    }

    .tag.rakyat {
      background-color:rgb(193, 66, 66);
    }

    .tag.mitos {
      background-color:rgb(102, 193, 66);
    }

    
    .thead th {
            background-color: #C3DFE6;
            color: black;
        }
  </style>
</head>
<body>
<div id="content">
<div class="container mt-5">
    <header class="header">
      <h1 class="title">MalayanFolktalesHub</h1>
      <button class="logout" onclick="window.location.href='logout.php'">Log Out</button>
    </header>

      <h2 class="subtitle">Generate Report</h2>
      <div class="table-responsive table-container">  
      <table class="report-table">
      <thead class="thead">
          <tr>
            <th>Folktales Title</th>
            <th>Genre</th>
            <th>Folktales Origin</th>
          </tr>
        </thead>
        <tbody>
        <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["title"] . "</td>";
                            echo "<td><span class='tag " . strtolower($row["genre"]) . "'>" . $row["genre"] . "</span></td>";
                            echo "<td>" . $row["state"] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No results found.</td></tr>";
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

      
    </main>
  </div>
  <?php $conn->close(); ?>
</body>
</html>

