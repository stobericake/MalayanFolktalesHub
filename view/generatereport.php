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

.controls {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 15px;
}

.search {
  flex-grow: 1;
  padding: 8px;
  border: 1px solid #ddd;
  border-radius: 4px;
}

.filter-button,
.customize-button {
  background-color: #007b5e;
  color: #fff;
  border: none;
  padding: 8px 12px;
  border-radius: 4px;
  cursor: pointer;
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
  background-color: #17a2b8;
}

.tag.jenaka {
  background-color: #ffc107;
}

.tag.fabel {
  background-color: #fd7e14;
}

.tag.mitos {
  background-color: #6f42c1;
}

.pagination {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-top: 10px;
}

.rows-per-page {
  padding: 5px;
  border: 1px solid #ddd;
  border-radius: 4px;
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


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Generate Report</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="container">
    <header class="header">
      <h1 class="title">MalayanFolktalesHub</h1>
      <button class="logout">Log Out</button>
    </header>

    <main>
      <h2 class="subtitle">Generate Report</h2>
      <div class="controls">
        <input type="text" class="search" placeholder="Search">
        <button class="filter-button">Filter</button>
        <button class="customize-button">Customize</button>
      </div>

      <table class="report-table">
        <thead>
          <tr>
            <th>Folktales Title</th>
            <th>Genre</th>
            <th>Folktales Origin</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Siapa Lebih Bijak</td>
            <td><span class="tag teladan">Teladan</span></td>
            <td>Kedah</td>
          </tr>
          <tr>
            <td>Gajah Yang Setia</td>
            <td><span class="tag legenda">Legenda</span></td>
            <td>Perak</td>
          </tr>
          <tr>
            <td>Awang Yang Lurus</td>
            <td><span class="tag jenaka">Jenaka</span></td>
            <td>Perlis</td>
          </tr>
          <tr>
            <td>Asal Usul Pekan Teluk Sengat</td>
            <td><span class="tag legenda">Legenda</span></td>
            <td>Johor</td>
          </tr>
          <tr>
            <td>Seuncang Padi</td>
            <td><span class="tag teladan">Teladan</span></td>
            <td>Pahang</td>
          </tr>
          <tr>
            <td>Mabuk Laut Mabuk Darat</td>
            <td><span class="tag jenaka">Jenaka</span></td>
            <td>Pulau Pinang</td>
          </tr>
          <tr>
            <td>Tempua Bersarang Rendah</td>
            <td><span class="tag fabel">Fabel</span></td>
            <td>Nasional</td>
          </tr>
          <tr>
            <td>Gajah Bermata Kecil</td>
            <td><span class="tag fabel">Fabel</span></td>
            <td>Negeri Sembilan</td>
          </tr>
          <tr>
            <td>Buntat Teloh</td>
            <td><span class="tag mitos">Mitos</span></td>
            <td>Sarawak</td>
          </tr>
          <tr>
            <td>Isteri Yang Bongkak</td>
            <td><span class="tag teladan">Teladan</span></td>
            <td>Selangor</td>
          </tr>
        </tbody>
      </table>

      <div class="pagination">
        <select class="rows-per-page">
          <option>10</option>
          <option>20</option>
          <option>50</option>
        </select>
        <span>Rows per page</span>
        <span class="page-info">Displaying 1-10 of 100</span>
        <button class="pagination-button">&lt;</button>
        <button class="pagination-button">&gt;</button>
      </div>
    </main>
  </div>
</body>
</html>
