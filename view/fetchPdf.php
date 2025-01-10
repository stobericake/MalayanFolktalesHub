<?php
// Fetch the PDF data from the database
$servername = "localhost"; // Update as necessary
$username = "root";        // Update as necessary
$password = "";            // Update as necessary
$dbname = "malayanfolktales_hub"; // Update with your database name

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$pdfID = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sql = "SELECT pdfID, pdfPath FROM test WHERE pdfID = 1"; // Replace `your_table` with your table name
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $pdfData = base64_encode($row['pdfPath']); // Convert the blob to base64
} else {
    die("PDF not found.");
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Preview</title>
    <style>
        #pdf-render {
            width: 80%;
            margin: 20px auto;
            border: 1px solid #ccc;
        }
        .controls {
            text-align: center;
            margin: 10px;
        }
        button {
            padding: 10px;
            margin: 0 5px;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
</head>
<body>
    <h1 style="text-align: center;">PDF Preview</h1>
    <div class="controls">
        <button id="prev-page">Previous Page</button>
        <span id="page-info">Page <span id="current-page">1</span> of <span id="total-pages">1</span></span>
        <button id="next-page">Next Page</button>
    </div>
    <canvas id="pdf-render"></canvas>

    <script>
        const pdfData = 'data:application/pdf;base64,<?php echo $pdfData; ?>';
        const pdfjsLib = window['pdfjs-dist/build/pdf'];

        // PDF.js setup
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';
        let pdfDoc = null;
        let currentPage = 1;
        const canvas = document.getElementById('pdf-render');
        const ctx = canvas.getContext('2d');

        // Load the PDF
        pdfjsLib.getDocument(pdfData).promise.then((doc) => {
            pdfDoc = doc;
            document.getElementById('total-pages').textContent = pdfDoc.numPages;
            renderPage(currentPage);
        });

        // Render a page
        function renderPage(pageNum) {
            pdfDoc.getPage(pageNum).then((page) => {
                const viewport = page.getViewport({ scale: 1.5 });
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                const renderContext = {
                    canvasContext: ctx,
                    viewport: viewport,
                };
                page.render(renderContext);
                document.getElementById('current-page').textContent = pageNum;
            });
        }

        // Navigate between pages
        document.getElementById('prev-page').addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                renderPage(currentPage);
            }
        });

        document.getElementById('next-page').addEventListener('click', () => {
            if (currentPage < pdfDoc.numPages) {
                currentPage++;
                renderPage(currentPage);
            }
        });
    </script>
    
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
</body>
</html>

