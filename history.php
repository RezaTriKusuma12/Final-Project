<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

require "koneksi.php";

// Ambil daftar gedung dari database
$gedungQuery = mysqli_query($koneksi, "SELECT DISTINCT lokasi FROM data_udara");
$gedungList = [];
while ($g = mysqli_fetch_assoc($gedungQuery)) {
    $gedungList[] = $g['lokasi'];
}

// Default gedung = gedung pertama jika belum dipilih
$selectedGedung = isset($_GET['gedung']) ? $_GET['gedung'] : $gedungList[0];

// Filter bulan (opsional)
$filterBulan = "";
$bulanDipilih = "";

if (isset($_GET['bulan']) && $_GET['bulan'] != "") {
    $bulanDipilih = $_GET['bulan'];
    $filterBulan = " AND MONTH(timestamp) = $bulanDipilih ";
}

// Query data history
$sql = "
    SELECT 
        DATE(timestamp) AS tgl,
        AVG(kualitas_udara) AS avg_polusi,
        AVG(suhu) AS avg_suhu
    FROM data_udara
    WHERE lokasi = '$selectedGedung'
    $filterBulan
    GROUP BY DATE(timestamp)
    ORDER BY DATE(timestamp)
";

$result = mysqli_query($koneksi, $sql);

$tanggal = [];
$polusi = [];
$suhu = [];

while ($row = mysqli_fetch_assoc($result)) {
    $tanggal[] = $row['tgl'];
    $polusi[] = $row['avg_polusi'];
    $suhu[] = $row['avg_suhu'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>History Polusi</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    body { font-family: Arial; padding: 20px; }
    select { padding: 8px; }
    table { border-collapse: collapse; width: 60%; margin-top: 20px; }
    th, td { border: 1px solid #aaa; padding: 8px; text-align: center; }
    .filter-bar { display: flex; align-items: center; gap: 20px; margin-bottom: 20px; }
</style>

</head>

<body>

<h2>📊 History Polusi – Per Gedung</h2>
<div style="margin-bottom: 20px;">
    <a href="index.php">← Kembali ke Dashboard</a>
</div>

<!-- Filter Bar -->
<div class="filter-bar">

    <!-- Pilih Gedung -->
    <form method="GET">
        <label><b>Pilih Gedung:</b></label>
        <select name="gedung" onchange="this.form.submit()">
            <?php foreach ($gedungList as $g): ?>
                <option value="<?= $g ?>" <?= $g == $selectedGedung ? 'selected' : '' ?>>
                    <?= $g ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Jika pindah gedung, bulan tetap terbawa -->
        <?php if ($bulanDipilih != ""): ?>
            <input type="hidden" name="bulan" value="<?= $bulanDipilih ?>">
        <?php endif; ?>
    </form>

    <!-- Pilih Bulan -->
    <form method="GET">
        <input type="hidden" name="gedung" value="<?= $selectedGedung ?>">

        <label><b>Pilih Bulan:</b></label>
        <select name="bulan" onchange="this.form.submit()">
            <option value="">Semua Bulan</option>
            <?php
            for ($i = 1; $i <= 12; $i++) {
                $selected = ($bulanDipilih == $i) ? 'selected' : '';
                echo "<option value='$i' $selected>" . date("F", mktime(0,0,0,$i,1)) . "</option>";
            }
            ?>
        </select>
    </form>

</div>

<!-- Tampilkan grafik hanya jika ada data -->
<?php if (count($tanggal) > 0): ?>
    <canvas id="grafikGabungan" width="1000" height="400"></canvas>

    <script>
    const tanggal = <?php echo json_encode($tanggal); ?>;
    const polusi = <?php echo json_encode($polusi); ?>;
    const suhu = <?php echo json_encode($suhu); ?>;

    new Chart(document.getElementById('grafikGabungan'), {
        type: 'line',
        data: {
            labels: tanggal,
            datasets: [
                {
                    label: "PM2.5 (µg/m³)",
                    data: polusi,
                    borderWidth: 3,
                    tension: 0.4,
                    yAxisID: 'y1'
                },
                {
                    label: "Suhu (°C)",
                    data: suhu,
                    borderWidth: 3,
                    borderDash: [5, 5],
                    tension: 0.4,
                    yAxisID: 'y2'
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y1: {
                    type: 'linear',
                    position: 'left',
                    beginAtZero: true,
                    title: { display: true, text: "Polusi PM2.5" }
                },
                y2: {
                    type: 'linear',
                    position: 'right',
                    beginAtZero: false,
                    title: { display: true, text: "Suhu (°C)" },
                    grid: { drawOnChartArea: false }
                }
            }
        }
    });
    </script>

<?php else: ?>
    <h3 style="color: red; margin-top: 20px;">
        ❗ Tidak ada data untuk bulan ini.
    </h3>
<?php endif; ?>


<!-- Tabel Data -->
<h3>📄 Detail Data Per Hari – <?= $selectedGedung ?></h3>

<?php if (count($tanggal) > 0): ?>
<table>
    <tr>
        <th>Tanggal</th>
        <th>Rata-rata Polusi (PM2.5)</th>
        <th>Rata-rata Suhu (°C)</th>
    </tr>

    <?php for ($i = 0; $i < count($tanggal); $i++): ?>
    <tr>
        <td><?= $tanggal[$i] ?></td>
        <td><?= number_format($polusi[$i], 1) ?></td>
        <td><?= number_format($suhu[$i], 1) ?></td>
    </tr>
    <?php endfor; ?>
</table>

<?php else: ?>
    <p>Tidak ada data untuk ditampilkan.</p>
<?php endif; ?>

</body>
</html>
