<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Home Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: white;
      color: #102a43;
      font-family: Arial, sans-serif;
      height: 100vh;
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .container-fluid {
      display: flex;
      justify-content: space-between;
      align-items: center;
      width: 90%;
      max-width: 1200px;
      height: 90%;
    }

    .card {
      background-color: #f2f9fc;
      border: none;
      border-radius: 10px;
      padding: 20px;
      height: 100%;
      overflow-y: auto;
    }

    .btn-genre, .btn-state {
      width: 100%;
      margin-bottom: 10px;
      background-color: #ffffff;
      border: none;
      border-radius: 5px;
      color: #102a43;
      text-align: center;
      font-size: 14px;
      font-weight: bold;
      padding: 10px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .btn-genre:hover, .btn-state:hover {
      background-color: #c0dce9;
    }

    .user-info {
      text-align: center;
      padding: 20px;
      background-color: #f2f9fc;
      border-radius: 10px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      width: 80%;
      max-width: 300px;
    }

    .user-info img {
      border-radius: 50%;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>
  <div class="container-fluid">
    <!-- Main Content -->
    <div class="py-4" style="width: 60%;">
      <div class="row mt-4">
        <!-- Folktale Genre -->
        <div class="col-md-6">
          <div class="card">
            <h5 class="text-center mb-3">FOLKTALE GENRE</h5>
            <button class="btn btn-genre">Legend</button>
            <button class="btn btn-genre">Moral</button>
            <button class="btn btn-genre">History</button>
            <button class="btn btn-genre">Joke</button>
            <button class="btn btn-genre">Fable</button>
            <button class="btn btn-genre">Allegory</button>
            <button class="btn btn-genre">Myth</button>
            <button class="btn btn-genre">Folk</button>
            <button class="btn btn-genre">Children</button>
            <button class="btn btn-genre">Adventure</button>
            <button class="btn btn-genre">Example</button>
          </div>
        </div>

        <!-- Folktale State -->
        <div class="col-md-6">
          <div class="card">
            <h5 class="text-center mb-3">FOLKTALE STATE</h5>
            <button class="btn btn-state">Johor</button>
            <button class="btn btn-state">Kedah</button>
            <button class="btn btn-state">Kelantan</button>
            <button class="btn btn-state">Melaka</button>
            <button class="btn btn-state">Negeri Sembilan</button>
            <button class="btn btn-state">Pahang</button>
            <button class="btn btn-state">Pulau Pinang</button>
            <button class="btn btn-state">Perak</button>
            <button class="btn btn-state">Perlis</button>
            <button class="btn btn-state">Sabah</button>
            <button class="btn btn-state">Sarawak</button>
            <button class="btn btn-state">Selangor</button>
            <button class="btn btn-state">Terengganu</button>
            <button class="btn btn-state">Nasional</button>
          </div>
        </div>
      </div>
    </div>

    <!-- User Info -->
    <div class="py-4" style="width: 30%; display: flex; align-items: center; justify-content: center;">
      <div class="user-info">
        <img src="https://via.placeholder.com/80" alt="User Image">
        <h5>Nur Ain</h5>
        <p>+019-9452728</p>
        <p>abc123@gmail.com</p>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
