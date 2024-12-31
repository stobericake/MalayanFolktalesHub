<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Folktales Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom styles for the table */
        thead th {
            background-color: #e3f2fd; /* Light blue background */
            color: #000; /* Black text */
        }
        tbody tr:hover {
            background-color: #f1f1f1; /* Light grey on hover */
        }
        .btn-action {
            font-size: 1rem;
            padding: 0.3rem;
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?> 
<div id="content">
    <div class="container mt-3">
        <h1 class="mb-2">Folktales Managements</h1>
        <div class="d-flex">
            <!-- New Folktale Button -->
            <button class="btn btn-success btn-sm">+ New</button>
            <!-- Search Bar -->
            <div class="input-group" style="width: 250px;">
                <input type="text" class="form-control" placeholder="Search">
                <button class="btn btn-outline-secondary">
                    <i class="bi bi-search"></i> <!-- Bootstrap Icons -->
                </button>
            </div>
        </div>
        
        <!-- Total Items -->
        <p>Showing <strong>1-20</strong> of <strong>514</strong> items.</p>
        
        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Actions</th>
                        <th>Title</th>
                        <th>State</th>
                        <th>Genre</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Example data for demonstration
                    $data = [
                        ["title" => "Seuncang Padi", "state" => "Kedah", "genre" => "Lagenda"],
                        ["title" => "Tempura Bersarang Rendah", "state" => "Pahang", "genre" => "Teladan"],
                        // Add more data here
                    ];
                    foreach ($data as $row) {
                        echo "<tr>
                                <td>
                                    <button class='btn btn-primary btn-sm btn-action'>
                                        <i class='bi bi-pencil'></i>
                                    </button>
                                    <button class='btn btn-danger btn-sm btn-action'>
                                        <i class='bi bi-trash'></i>
                                    </button>
                                </td>
                                <td>{$row['title']}</td>
                                <td>{$row['state']}</td>
                                <td>{$row['genre']}</td>
                                
                            </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <nav>
            <ul class="pagination justify-content-center">
                <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
        </nav>
    </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Icons -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.js"></script>
</body>
</html>
