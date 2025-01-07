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
  <link rel="stylesheet" href="styles.css">
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
      background-color:rgb(23, 184, 109);
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
      background-color:rgb(193, 134, 66);
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

    .pagination {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-top: 10px;
    }

    .page-info {
      margin-left: auto;
      font-size: 14px;
    }

    .pagination-button {
      background-color: #fff;
      border: 1px solid #ddd;
      padding: 5px 10px;
      cursor: pointer;
      border-radius: 4px;
    }
  </style>
</head>
<body>
  <div class="container">
    <header class="header">
      <h1 class="title">MalayanFolktalesHub</h1>
      <button class="logout" onclick="window.location.href='logout.php'">Log Out</button>
    </header>

    <main>
      <h2 class="subtitle">Generate Report</h2>

      <table class="report-table">
        <thead>
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

      <div class="pagination">
        <?php if ($page > 1): ?>
          <a href="?page=<?php echo $page - 1; ?>" class="pagination-button">&lt;</a>
        <?php endif; ?>

        <?php if ($page < $totalPages): ?>
          <a href="?page=<?php echo $page + 1; ?>" class="pagination-button">&gt;</a>
        <?php endif; ?>

        <span class="page-info">Displaying <?php echo $start + 1; ?>-<?php echo min($start + $limit, $totalRecords); ?> of <?php echo $totalRecords; ?></span>
      </div>

      
    </main>
  </div>
  <?php $conn->close(); ?>
</body>
</html>

