<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookmarks</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        .total-bookmarks {
            font-size: 1.5rem;
            font-weight: bold;
            color: #5e72e4;
            margin-bottom: 20px;
        }
        .table-container {
            margin-top: 20px;
        }
        .pagination {
            justify-content: center;
        }
        .search-bar {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .search-bar input, .search-bar select {
            flex: 1;
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

    <!-- Total Bookmarks -->
    <div class="d-flex justify-content-between align-items-center">
        <div class="total-bookmarks">
            📖 Total Bookmarks: <span>12</span>
        </div>
    </div>

    <!-- Search Filters -->
    <div class="search-bar">
        <input type="text" class="form-control" placeholder="Search by title...">
        <select class="form-select">
            <option selected>Select State</option>
            <option>Kedah</option>
            <option>Perak</option>
            <option>Johor</option>
            <!-- Add more states here -->
        </select>
        <select class="form-select">
            <option selected>Select Genre</option>
            <option>Teladan</option>
            <option>Lagenda</option>
            <option>Fabel</option>
            <!-- Add more genres here -->
        </select>
        <button class="btn btn-success">Search</button>
    </div>

    <!-- Bookmarks Table -->
    <div class="table-responsive table-container">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>State</th>
                    <th>Genre</th>
                </tr>
            </thead>
            <tbody>
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
                <!-- Add more rows here -->
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
</body>
</html>
