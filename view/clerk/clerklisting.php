<?php
session_start();

// Database connection
include '../dbconn.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';

// Fetch total number of folktales (with search condition if applicable)
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

// Fetch folktales with pagination and search
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
    <title>Folktales Management</title>
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
            color: #fff;
            padding-top: 110px;
        }
        
        .container {
            margin-bottom: 80px;
        }

        .container h1 {
            font-family: 'Fredoka One', cursive;
            color: black;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }
        .new-bar {
            display: inline-block; /* Make the link behave like a block element */
            padding: 10px 20px;
            border: none;
            background-color: rgb(46, 182, 114);
            color: white;
            cursor: pointer;
            border-radius: 25px;
            text-decoration: none; /* Remove underline from the link */
            font-size: 16px; /* Adjust font size as needed */
            text-align: center;
            transition: background-color 0.3s ease, transform 0.3s ease; /* Add transform for hover effect */
        }

        .new-bar:hover {
            background-color: rgb(36, 142, 89); /* Darker shade for hover */
            transform: scale(1.05); /* Slightly enlarge on hover */
        }

        .new-bar:active {
            background-color: rgb(28, 110, 69); /* Even darker shade for active state */
            transform: scale(0.95); /* Slightly shrink on click */
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

        /* Action icons styling */
        .action-icons i {
            cursor: pointer;
            font-size: 13px; /* Icon size */
            color: #6c757d; /* Default icon color */
        }

        .action-icons i:hover {
            color: #007bff; /* Blue color on hover */
        }
        .search-bar {
            display: inline-block;
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
        .btn-primary {
            background-color:rgb(138, 194, 228);
            border: none;
            color: black;
            text-decoration: none;
            border-radius: 25px;
            padding: 5px 10px;
            font-size: 14px;
            transition: background-color 0.3s ease, transform 0.3s ease;
            margin-right: 5px;
        }

        .btn-primary:hover {
            background-color: #234956;
            transform: scale(1.05);
        }

        .btn-secondary {
            background-color:rgb(150, 156, 159);
            border: none;
            color: black;
            text-decoration: none;
            border-radius: 25px;
            padding: 5px 10px;
            font-size: 14px;
            transition: background-color 0.3s ease, transform 0.3s ease;
            margin-right: 5px;
        }

        .btn-secondary:hover {
            background-color:rgb(85, 93, 96);
            transform: scale(1.05);
        }

        .btn-remove {
            background-color:rgb(228, 138, 138);
            border: none;
            color: black;
            text-decoration: none;
            border-radius: 25px;
            padding: 5px 10px;
            font-size: 14px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn-remove:hover {
            background-color:rgb(86, 35, 35);
            transform: scale(1.05);
        }

        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            text-align: center;
        }

        .popup.success {
            border: 1px solid rgb(175, 227, 177);
        }

        .popup.error {
            border: 1px solid rgb(249, 103, 93);
        }

        .popup-icon {
            font-size: 50px;
            margin-bottom: 10px;
        }

        .popup.success .popup-icon {
            color: rgb(175, 227, 177);
        }

        .popup.error .popup-icon {
            color: rgb(249, 103, 93);
        }

        .popup-close {
            margin-top: 10px;
            padding: 5px 10px;
            background-color: rgb(175, 227, 177);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .popup-close:hover {
            background-color: #45a049;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 5px; 
        }

        .pagination .page-item {
            margin: 0 2px; /* Spacing between pagination items */
        }

        .pagination .page-link {
            color:rgb(140, 148, 157); /* Blue text color */
            background-color: #fff; /* White background */
            border: 1px solid #dee2e6; /* Light gray border */
            border-radius: 20px; /* Rounded corners */
            padding: 8px 12px; /* Padding for links */
            font-size: 14px; /* Font size */
            transition: all 0.3s ease; /* Smooth transition for hover effects */
        }
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .search-box input {
                width: 200px; /* Smaller search box on mobile */
            }

            .table thead th,
            .table tbody td {
                padding: 8px 10px; /* Smaller padding on mobile */
            }
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
<div id="content">
    <div class="container">
        <h1>Folktales Management</h1>
        <div class="d-flex align-items-center">
            <a class="new-bar" href="addfolktales.php">+ New</a>
            <div class="ms-auto">
            <form method="GET" action="" class="search-bar">
                <input type="text" name="search" placeholder="Enter title / genre / origin" value="<?php echo htmlspecialchars($search); ?>">
                <input type="hidden" name="page" value="1"> 
                <button type="submit"><i class="bi bi-search"></i> Search</button>
                <?php if (!empty($search)) : ?>
                    <a href="clerklisting.php" class="clear-search">Clear Search</a>
                <?php endif; ?>
            </form>
            </div>
        </div>
        <p>Showing <strong><?php echo $start + 1; ?></strong> to <strong><?php echo min($start + $recordsPerPage, $totalRecords); ?></strong> of <strong><?php echo $totalRecords; ?></strong> items.</p>
        
        <div class="table-responsive table-container mb-3">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                    <th>Bil</th>
                        <th style="text-align:left">Title</th>
                        <th>Origin</th>
                        <th>Genre</th>
                        <th style="width:10%">View detail</th>
                        <th style="width:15%">Action</th>
                    </tr>
                </thead>
                <tbody id="bookmarks-table-body">
                    <?php
                    if ($result && $result->num_rows > 0) {
                        $bil = $start + 1; // Serial number starts from the current page start
                        while ($row = $result->fetch_assoc()) {
                            $editLink = "editfolktales.php?id=" . $row['folktaleID']; // Edit URL
                            echo "<tr>
                                <td>$bil</td>
                                <td style='text-align:left'>{$row['title']}</td>
                                <td>{$row['state']}</td>
                                <td>{$row['genre']}</td>
                                <td>
                                    <a href='folktaledetails.php?id={$row['folktaleID']}' class='btn-primary btn-sm'><i class='bi bi-eye'></i>
                                    </a>
                                </td>
                                <td>
                                    <a href='$editLink' title='Edit' class='btn-primary btn-sm mb-1'>
                                        <i class='bi bi-pencil-square'></i>
                                    </a>
                                    <a href='#' class='btn-remove btn-sm delete-btn mb-1' title='Delete' data-bs-toggle='modal' data-bs-target='#deleteConfirmationModal' data-id='{$row['folktaleID']}'>
                                        <i class='bi bi-trash3'></i>
                                    </a>
                                </td>
                            </tr>";
                            $bil++;
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center'>No folktales found or unable to fetch data</td></tr>";
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this folktale? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>


<!-- Popup HTML -->
<div class="overlay" id="overlay"></div>
<div class="popup" id="popup">
    <div class="popup-icon">🎉</div>
    <p id="popup-message"></p>
    <button class="popup-close" onclick="closePopup()">Close</button>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Function to handle the delete confirmation
    let folktaleIDToDelete = null;

    // Set the folktale ID when the delete button is clicked
    document.querySelectorAll('[data-bs-target="#deleteConfirmationModal"]').forEach(button => {
        button.addEventListener('click', () => {
            folktaleIDToDelete = button.getAttribute('data-id');
        });
    });

    // Handle the delete button in the modal
    document.getElementById('confirmDelete').addEventListener('click', () => {
        if (folktaleIDToDelete) {
            // Send AJAX request to delete the folktale
            fetch(`delete_folktale.php?id=${folktaleIDToDelete}`, { method: "GET" })
                .then(response => response.text())
                .then(data => {
                    if (data.trim() === "success") {
                        alert("Folktale deleted successfully!");
                        location.reload(); // Reload the page to refresh the table
                    } else {
                        alert("Error deleting folktale: " + data);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("An error occurred while trying to delete the folktale.");
                });
        }
        // Close the modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
        modal.hide();
    });
</script>

<script>
    function filterTable() {
        const input = document.getElementById("searchInput").value.toLowerCase();
        const rows = document.querySelectorAll("#bookmarks-table-body tr");
        
        // Update the URL for pagination purposes with the search term
        const url = new URL(window.location.href);
        url.searchParams.set("search", input);  // Update the search query parameter in URL
        history.pushState(null, "", url);  // Change URL without reloading the page
        
        rows.forEach(row => {
            const title = row.cells[1].textContent.toLowerCase();
            const state = row.cells[2].textContent.toLowerCase();
            const genre = row.cells[3].textContent.toLowerCase();
            row.style.display = (title.includes(input) || state.includes(input) || genre.includes(input)) ? "" : "none";
        });
    }
</script>

<script>
    // Handle the confirmation button click
    document.getElementById('confirmAdd').addEventListener('click', function () {
        // Submit the form
        document.getElementById('folktaleForm').submit();
    });

        // Function to show the popup
        function showPopup(message, type) {
        const popup = document.getElementById('popup');
        const overlay = document.getElementById('overlay');
        const popupMessage = document.getElementById('popup-message');

        popupMessage.textContent = message;
        popup.className = `popup ${type}`;
        popup.style.display = 'block';
        overlay.style.display = 'block';
    }

    // Function to close the popup
    function closePopup() {
        const popup = document.getElementById('popup');
        const overlay = document.getElementById('overlay');
        popup.style.display = 'none';
        overlay.style.display = 'none';

        // Redirect to clerklisting.php
        window.location.href = 'clerklisting.php';    
    }

    // Check for session message and show popup
    <?php if (isset($_SESSION['message'])): ?>
        showPopup("<?= $_SESSION['message'] ?>", "<?= $_SESSION['message_type'] ?>");
        <?php
        // Clear the session message
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
        ?>
    <?php endif; ?>
</script>

</body>
</html>
