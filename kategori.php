<?php
require "koneksi.php";

// Ambil data terbaru tiap lokasi + JOIN kategori_udara
$sql = "
    SELECT d1.*, k.nama_kategori, k.warna
    FROM data_udara d1
    JOIN (
        SELECT lokasi, MAX(timestamp) AS max_time
        FROM data_udara
        GROUP BY lokasi
    ) d2
        ON d1.lokasi = d2.lokasi AND d1.timestamp = d2.max_time
    LEFT JOIN kategori_udara k
        ON d1.kualitas_udara BETWEEN k.pm_min AND k.pm_max
    ORDER BY d1.lokasi
";

$result = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kategori Kualitas Udara</title>

<style>
body {
    font-family: "Segoe UI", Arial, sans-serif;
    background: #eef2f5;
    margin: 0;
    padding: 20px;
}

h2 {
    text-align: center;
    margin-bottom: 25px;
    font-size: 28px;
    color: #1a237e;
}

table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 3px 15px rgba(0,0,0,0.1);
}

th {
    background: #1e88e5;
    color: white;
    padding: 14px;
    font-size: 16px;
}

td {
    padding: 12px;
    text-align: center;
    border-bottom: 1px solid #f0f0f0;
    font-size: 15px;
}

tbody tr:hover {
    background: #f5faff;
    transition: background .2s;
}

.badge {
    padding: 7px 14px;
    color: white;
    border-radius: 8px;
    font-weight: bold;
    display: inline-block;
    font-size: 14px;
}
</style>

</head>
<body>

<div id="header">
    <h2>🌍 Mapping Udara Kampus</h2> <br>
    <a href="index.php">← Kembali ke Dashboard</a>
</div>

<h2>Kategori Kualitas Udara (Realtime)</h2>

<table>
<thead>
<tr>
    <th>Lokasi</th>
    <th>Suhu (°C)</th>
    <th>PM2.5</th>
    <th>Kategori</th>
    <th>Update Terakhir</th>
</tr>
</thead>

<tbody>
<?php while ($row = mysqli_fetch_assoc($result)): ?>

    <?php 
        // PM 2.5 asli dari database
        $pm25 = $row["kualitas_udara"];

        // Kategori & warna hasil JOIN (fallback jika NULL)
        $kategori = $row["nama_kategori"] ?? "Tidak diketahui";
        $warna    = $row["warna"] ?? "#9E9E9E";
    ?>

    <tr>
        <td><?= $row["lokasi"] ?></td>
        <td><?= number_format($row["suhu"], 1) ?></td>

        <td><?= is_numeric($pm25) ? number_format($pm25, 1) . " µg/m³" : "-" ?></td>

        <td>
            <span class="badge" style="background: <?= $warna ?>;">
                <?= $kategori ?>
            </span>
        </td>

        <td><?= $row["timestamp"] ?></td>
    </tr>

<?php endwhile; ?>
</tbody>
</table>

</body>
</html>