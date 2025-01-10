<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f9fc;
        }

        .container {
            width: 90%;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
        }

        .header input[type="text"] {
            width: 300px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .profile {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .profile-card, .stat-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            flex: 1;
            text-align: center;
        }

        .profile-info img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .profile-info h3 {
            margin: 5px 0;
        }

        .stat-card .icon {
            font-size: 36px;
            color: #007bff;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        table thead {
            background: #f1f1f1;
        }

        table th, table td {
            padding: 15px;
            text-align: left;
        }

        table th {
            font-size: 14px;
            color: #555;
        }

        table tbody tr:nth-child(even) {
            background: #f9f9f9;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 5px;
            margin: 20px 0;
        }

        .pagination a {
            text-decoration: none;
            padding: 10px 15px;
            margin: 0 5px;
            background: #007bff;
            color: #fff;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        .pagination a.active {
            background: #0056b3;
        }

        .pagination a:hover {
            background: #0056b3;
        }

        .page-item.disabled a {
            background: #ccc;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>User's Profile</h1>
            <input type="text" placeholder="Search">
        </div>
        
        <div class="profile">
            <div class="profile-card">
                <div class="profile-info">
                    <img src="https://via.placeholder.com/80" alt="Profile Picture">
                    <h3>Nur Ain</h3>
                    <p>+019-9452728</p>
                    <p>Email: abc123@gmail.com</p>
                </div>
            </div>

            <div class="stat-card">

                <h4>Total Bookmarks</h4>
                <i class="bi bi-bookmark-heart icon"></i>
                <p>12</p>
            </div>

            <div class="stat-card">
                <h4>Viewed Story</h4>
                <i class="bi bi-eye icon"></i>
                <p>25</p>
            </div>
        </div>

        <table>
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
                    <td>Asal Usul Pekan Teluk Sengat</td>
                    <td>Johor</td>
                    <td>Teladan</td>
                </tr>
                <tr>
                    <td>Secuncang Padi</td>
                    <td>Pahang</td>
                    <td>Legenda</td>
                </tr>
                <tr>
                    <td>Tempura Besarang Rendah</td>
                    <td>Nasional</td>
                    <td>Fabel</td>
                </tr>
                <tr>
                    <td>Buntat Teloh</td>
                    <td>Sarawak</td>
                    <td>Mitos</td>
                </tr>
                <tr>
                    <td>Pohon Kemuni</td>
                    <td>Pulau Pinang</td>
                    <td>Alegori</td>
                </tr>
                <tr>
                    <td>Peraduan Bercerita</td>
                    <td>Kedah</td>
                    <td>Jenaka</td>
                </tr>
            </tbody>
        </table>

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
</body>
</html>
