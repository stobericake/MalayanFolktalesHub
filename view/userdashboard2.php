<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Home Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .genre-state-panel {
            background-color: #C3DFE6;
            padding: 10px;
            border-radius: 10px;
        }
        .genre-state-panel button {
            width: 100%;
            margin-bottom: 10px;
            background-color: #fff3e0;
            border: none;
            border-radius: 5px;
            padding: 10px;

        }
        
        .profile-section {
            background-color: #f5f5f5;
            padding: 10px;
            border-radius: 10px;
            text-align: center;
        }
        .profile-section img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
        }
        .profile-section h5 {
            margin-top: 15px;
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?> 
<div id="content">
    <div class="container mt-1">
        <h3 class="mb-4">User Home Page</h3>
                <div class="row">
                    <div class="col-md-5 genre-state-panel">
                        <h5> FOLKTALE GENRE</h5>
                        <button>Legend</button>
                        <button>Moral</button>
                        <button>History</button>
                        <button>Joke</button>
                        <button>Fable</button>
                        <button>Allegory</button>
                        <button>Myth</button>
                        <button>Folk</button>
                        <button>Children</button>
                        <button>Adventure</button>
                        <button>Example</button>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-5 genre-state-panel">
                        <h5> FOLKTALE STATE</h5>
                        <button>Johor</button>
                        <button>Kedah</button>
                        <button>Kelantan</button>
                        <button>Melaka</button>
                        <button>Negeri Sembilan</button>
                        <button>Pahang</button>
                        <button>Pulau Pinang</button>
                        <button>Perak</button>
                        <button>Perlis</button>
                        <button>Sabah</button>
                        <button>Sarawak</button>
                        <button>Selangor</button>
                        <button>Terengganu</button>
                        <button>Nasional</button>
                    </div>
                </div>
            </div>

            
        </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
