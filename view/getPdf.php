<?php
// Database configuration
$servername = "localhost"; // Update as necessary
$username = "root";        // Update as necessary
$password = "";            // Update as necessary
$dbname = "malayanfolktales_hub"; // Update with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch data
$sql = "SELECT pdfID, pdfPath FROM test"; // Update `your_table` to the correct table name
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Display</title>
    <style>
        table {
            border-collapse: collapse;
            width: 80%;
            margin: 20px auto;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
        }
        a {
            color: #007BFF;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <h1 style="text-align:center;">PDF List</h1>
    <table>
        <thead>
            <tr>
                <th>PDF ID</th>
                <th>Download Link</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    $fileName = "file_" . $row["pdfID"] . ".pdf";
                    $filePath = "uploads/" . $fileName;

                    // Save the PDF to a file
                    file_put_contents($filePath, $row["pdfPath"]);

                    // Generate a link to download the PDF
                    echo "<tr>";
                    echo "<td>" . $row["pdfID"] . "</td>";
                    echo "<td><a href='$filePath' download='$fileName'>Download</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No records found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>

<?php
$conn->close();
?>
