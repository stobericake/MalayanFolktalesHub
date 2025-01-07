<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookmarks</title>
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
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?> 
<div id="content">
<div class="container mt-5">
    <h2 class="mb-4">Bookmarks</h2>

    <!-- Total Bookmarks Card -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="total-bookmarks-card">
                📖 <br>
                Total Bookmarks: <br>
                <span>12</span>
            </div>
        </div>
    </div>

    <!-- Search Filters -->
    <div class="search-bar">
    <input type="text" id="search-input" class="form-control" placeholder="Search by title..." oninput="filterTable()">

    </div>

    <!-- Bookmarks Table -->
    <div class="table-responsive table-container">
        <table class="table table-bordered table-striped">
            <thead class="thead">
                <tr>
                    <th>Title</th>
                    <th>State</th>
                    <th>Genre</th>
                </tr>
            </thead>
            <tbody id="bookmarks-table-body">
                <tr>
                    <td>Siapa Lebih Bijak</td>
                    <td>Kedah</td>
                    <td>Teladan</td>
                </tr>
                <tr>
                    <td>Gajah Yang Setia</td>
                    <td>Perak</td>
                    <td>Teladan</td>
                </tr>
                <tr>
                    <td>Awang Yang Lurus</td>
                    <td>Perlis</td>
                    <td>Lagenda</td>
                </tr>
                <tr>
                    <td>Asal Usul Pekan Teluk Sengat</td>
                    <td>Johor</td>
                    <td>Teladan</td>
                </tr>
                <tr>
                    <td>Seuncang Padi</td>
                    <td>Pahang</td>
                    <td>Lagenda</td>
                </tr>
                
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item"><a class="page-link" href="#">Next</a></li>
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
            const title = row.cells[0].textContent.toLowerCase();
            if (title.includes(searchInput)) {
                row.style.display = ''; // Show matching rows
            } else {
                row.style.display = 'none'; // Hide non-matching rows
            }
        });
    }
</script>
</body>
</html>
