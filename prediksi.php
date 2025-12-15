<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

require "koneksi.php";

// ============================
// Ambil daftar gedung unik
// ============================
$gedungQuery = mysqli_query($koneksi, "SELECT DISTINCT lokasi FROM data_udara");
$gedungList = [];
while ($g = mysqli_fetch_assoc($gedungQuery)) {
    $gedungList[] = $g['lokasi'];
}

$selectedGedung = isset($_GET['gedung']) ? $_GET['gedung'] : $gedungList[0];

// ============================
// Ambil data 30 hari terakhir
// ============================
$sql = "
    SELECT 
        DATE(timestamp) AS tgl,
        AVG(kualitas_udara) AS avg_polusi
    FROM data_udara
    WHERE lokasi = '$selectedGedung'
    GROUP BY DATE(timestamp)
    ORDER BY DATE(timestamp) DESC
    LIMIT 30
";

$result = mysqli_query($koneksi, $sql);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row['avg_polusi'];
}

if (count($data) < 3) {
    $data = array_merge($data, $data);
}

function prediksi($data) {
    $window = 7;
    $total = 0; 
    $count = 0;

    for ($i = 0; $i < $window; $i++) {
        if (!isset($data[$i])) break;
        $total += $data[$i];
        $count++;
    }

    return ($count == 0) ? 0 : $total / $count;
}

// ============================
// Tanggal prediksi
// ============================
$tanggal_prediksi = date("Y-m-d", strtotime("+1 day"));
$jam_sibuk = ["08:00", "12:00", "16:00", "18:00"];
$prediksiBesok = [];

foreach ($jam_sibuk as $jam) {
    $nilai = prediksi($data);
    $prediksiBesok[$jam] = $nilai;
}

function kategori($nilai) {
    if ($nilai <= 50) return "Baik";
    if ($nilai <= 100) return "Sedang";
    if ($nilai <= 150) return "Tidak Sehat";
    if ($nilai <= 200) return "Sangat Tidak Sehat";
    return "Berbahaya";
}

// ============================
// SIMPAN KE DATABASE
// ============================
$id_user = $_SESSION["user"]["id_user"];
foreach ($prediksiBesok as $jam => $nilai) {

    $kategori_polusi = kategori($nilai);
    $waktu_full = $tanggal_prediksi . " " . $jam . ":00";

    $queryInsert = "
        INSERT INTO prediksi_polusi (id_user, lokasi, waktu, nilai_polusi, kategori_polusi)
        VALUES ('$id_user', '$selectedGedung', '$waktu_full', '$nilai', '$kategori_polusi')
    ";
    mysqli_query($koneksi, $queryInsert);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Prediksi Polusi</title>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, sans-serif;
        background: #f4f7fb;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 900px;
        margin: 40px auto;
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    h2 {
        margin-top: 0;
        font-size: 26px;
        color: #2c3e50;
    }

    a {
        text-decoration: none;
        color: #3498db;
        font-size: 15px;
    }

    form {
        margin-top: 20px;
        background: #eef3f9;
        padding: 15px;
        border-radius: 10px;
    }

    select {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 15px;
        background: white;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 25px;
        border-radius: 10px;
        overflow: hidden;
    }

    th {
        background: #3498db;
        color: white;
        padding: 12px;
        font-size: 15px;
    }

    td {
        padding: 10px;
        text-align: center;
        border-bottom: 1px solid #ddd;
        background: #ffffff;
    }

    tr:hover td {
        background: #f2f8ff;
    }

    .tag {
        padding: 5px 10px;
        border-radius: 20px;
        color: white;
        font-size: 13px;
        font-weight: bold;
    }

    .baik { background: #27ae60; }
    .sedang { background: #f1c40f; }
    .tidaksehat { background: #e67e22; }
    .sangatburuk { background: #c0392b; }
    .berbahaya { background: #8e44ad; }
</style>

</head>

<body>
<div class="container">

<h2>📈 Prediksi Polusi Udara</h2>
<a href="index.php">← Kembali Ke Dashboard</a>

<form method="GET">
    <label><b>Pilih Gedung:</b></label>
    <select name="gedung" onchange="this.form.submit()">
        <?php foreach ($gedungList as $g): ?>
            <option value="<?= $g ?>" <?= $g == $selectedGedung ? 'selected' : '' ?>>
                <?= $g ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>

<h3>Gedung: <?= $selectedGedung ?></h3>
<h3>Tanggal Prediksi: <?= $tanggal_prediksi ?></h3>

<table>
    <tr>
        <th>Jam</th>
        <th>Prediksi Polusi (µg/m³)</th>
        <th>Kategori</th>
    </tr>

    <?php foreach ($prediksiBesok as $jam => $nilai): 
        $kat = kategori($nilai);
        $cls = strtolower(str_replace(" ", "", $kat));
    ?>
    <tr>
        <td><?= $jam ?></td>
        <td><?= number_format($nilai, 2) ?></td>
        <td><span class="tag <?= $cls ?>"><?= $kat ?></span></td>
    </tr>
    <?php endforeach; ?>
</table>

</div>
</body>
</html>