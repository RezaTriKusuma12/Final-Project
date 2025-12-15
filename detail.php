<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

require "koneksi.php";

// Ambil daftar lokasi (prefer data_udara, fallback history_polusi, fallback prediksi_polusi)
$lokasiList = [];
$q1 = mysqli_query($koneksi, "SELECT DISTINCT lokasi FROM data_udara");
while ($r = mysqli_fetch_assoc($q1)) $lokasiList[] = $r['lokasi'];

if (count($lokasiList) == 0) {
    $q2 = mysqli_query($koneksi, "SELECT DISTINCT lokasi FROM history_polusi");
    while ($r = mysqli_fetch_assoc($q2)) $lokasiList[] = $r['lokasi'];
}

if (count($lokasiList) == 0) {
    $q3 = mysqli_query($koneksi, "SELECT DISTINCT lokasi FROM prediksi_polusi");
    while ($r = mysqli_fetch_assoc($q3)) $lokasiList[] = $r['lokasi'];
}

// Pilih lokasi (GET param) atau default ke pertama
$selectedLokasi = isset($_GET['lokasi']) ? mysqli_real_escape_string($koneksi, $_GET['lokasi']) : ($lokasiList[0] ?? '');

// Fungsi warna kategori
function warnaKategori($k) {
    switch ($k) {
        case 'Baik': return '#27ae60';
        case 'Sedang': return '#f1c40f';
        case 'Tidak Sehat': return '#e67e22';
        case 'Sangat Tidak Sehat': return '#c0392b';
        case 'Berbahaya': return '#8e44ad';
        default: return '#7f8c8d';
    }
}

// =====================
// Polusi Terkini (data_udara)
// =====================
$latest = null;
if ($selectedLokasi) {
    $sqlLatest = "
        SELECT * FROM data_udara
        WHERE lokasi = '$selectedLokasi'
        ORDER BY timestamp DESC
        LIMIT 1
    ";
    $resLatest = mysqli_query($koneksi, $sqlLatest);
    if ($resLatest) $latest = mysqli_fetch_assoc($resLatest);
}

// =====================
// History (history_polusi) - 30 terakhir per lokasi
// =====================
$historyRows = [];
if ($selectedLokasi) {
    $sqlHistory = "
        SELECT tanggal, suhu, nilai_polusi
        FROM history_polusi
        WHERE lokasi = '$selectedLokasi'
        ORDER BY tanggal DESC
        LIMIT 30
    ";
    $resHistory = mysqli_query($koneksi, $sqlHistory);
    if ($resHistory) {
        while ($r = mysqli_fetch_assoc($resHistory)) $historyRows[] = $r;
    }
}

// =====================
// Prediksi (prediksi_polusi) - semua prediksi untuk lokasi
// =====================
$prediksiRows = [];
if ($selectedLokasi) {
    $sqlPred = "
        SELECT id_prediksi, waktu, nilai_polusi, kategori_polusi
        FROM prediksi_polusi
        WHERE lokasi = '$selectedLokasi'
        ORDER BY waktu ASC
    ";
    $resPred = mysqli_query($koneksi, $sqlPred);
    if ($resPred) {
        while ($r = mysqli_fetch_assoc($resPred)) $prediksiRows[] = $r;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Detail Lokasi - Polusi Udara</title>
<style>
    :root{
        --bg:#f4f8fb;
        --card:#fff;
        --accent:#0074D9;
        --muted:#7f8c8d;
    }
    body{margin:0;font-family: "Poppins", Arial, sans-serif;background:var(--bg);color:#222;}
    .wrap{max-width:1100px;margin:32px auto;padding:20px;}
    a.back{display:inline-block;margin-bottom:16px;color:var(--accent);text-decoration:none;font-weight:600}
    .top{display:flex;gap:16px;align-items:center;justify-content:space-between;margin-bottom:18px}
    .top .title{font-size:20px;font-weight:700}
    .controls {display:flex;gap:12px;align-items:center}
    select{padding:10px 12px;border-radius:8px;border:1px solid #d1d9e6;background:white;font-size:15px}
    button.primary{background:var(--accent);color:white;border:none;padding:10px 14px;border-radius:8px;cursor:pointer}
    /* cards */
    .cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:14px;margin-top:10px}
    .card{background:var(--card);padding:16px;border-radius:12px;box-shadow:0 6px 18px rgba(19,38,61,0.06)}
    .card h4{margin:0;font-size:14px;color:#495057}
    .card .big{font-size:28px;font-weight:700;margin-top:6px}
    .badge{display:inline-block;padding:6px 10px;border-radius:999px;color:white;font-weight:700;margin-top:8px}
    /* tabs */
    .tabs{margin-top:20px;background:var(--card);padding:12px;border-radius:12px;box-shadow:0 6px 18px rgba(19,38,61,0.06)}
    .tab-buttons{display:flex;gap:8px;margin-bottom:12px;flex-wrap:wrap}
    .tab-buttons button{background:transparent;border:1px solid #e6eef9;padding:8px 12px;border-radius:8px;cursor:pointer}
    .tab-buttons button.active{background:var(--accent);color:white;border-color:var(--accent)}
    .tab-content{background:white;padding:12px;border-radius:8px}
    table{width:100%;border-collapse:collapse;font-size:14px}
    th,td{padding:10px;border-bottom:1px solid #f0f2f6;text-align:center}
    th{background:#f8fbff;font-weight:700}
    .muted{color:var(--muted);font-size:13px}
    @media (max-width:720px){
        .top{flex-direction:column;align-items:flex-start}
    }
</style>
</head>
<body>
<div class="wrap">

    <a class="back" href="index.php">← Kembali ke Dashboard</a>

    <div class="top">
        <div>
            <div class="title">Detail Lokasi — <?= htmlspecialchars($selectedLokasi ?: '—') ?></div>
            <div class="muted" style="margin-top:6px">Lihat ringkasan, riwayat, dan prediksi per lokasi</div>
        </div>

        <div class="controls">
            <form id="formLokasi" method="GET" style="display:flex;gap:8px;align-items:center">
                <label class="muted">Pilih Lokasi:</label>
                <select name="lokasi" onchange="document.getElementById('formLokasi').submit()">
                    <?php foreach ($lokasiList as $loc): ?>
                        <option value="<?= htmlspecialchars($loc) ?>" <?= ($loc == $selectedLokasi) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($loc) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
    </div>

    <!-- CARDS RINGKASAN -->
    <div class="cards">
        <div class="card">
            <h4>Polusi Terkini (PM2.5)</h4>
            <div class="big">
                <?= $latest['kualitas_udara'] ?? '—' ?> <?= $latest ? 'µg/m³' : '' ?>
            </div>
            <div class="muted">Waktu: <?= $latest['timestamp'] ?? '—' ?></div>
        </div>

        <div class="card">
            <h4>Suhu</h4>
            <div class="big"><?= isset($latest['suhu']) ? number_format($latest['suhu'],1) . '°C' : '—' ?></div>
            <div class="muted">Kelembapan: <?= isset($latest['kelembapan']) ? number_format($latest['kelembapan'],0).'%' : '—' ?></div>
        </div>

        <div class="card">
            <h4>Kategori Udara</h4>
            <?php
                $kat = $latest['kategori_udara'] ?? null;
                $warna = warnaKategori($kat);
            ?>
            <div class="big">
                <?php if ($kat): ?>
                    <span class="badge" style="background:<?= $warna ?>"><?= htmlspecialchars($kat) ?></span>
                <?php else: ?>
                    —
                <?php endif; ?>
            </div>
            <div class="muted">Sumber: data_udara</div>
        </div>

        <div class="card">
            <h4>Prediksi Besok (Ringkas)</h4>
            <?php
                // show soonest prediksi for selected lokasi (if any)
                $nextPred = null;
                foreach ($prediksiRows as $p) {
                    if (!$nextPred) $nextPred = $p;
                }
            ?>
            <div class="big"><?= $nextPred ? number_format($nextPred['nilai_polusi'],2).' µg/m³' : '—' ?></div>
            <div class="muted"><?= $nextPred ? ($nextPred['waktu'].' — '.$nextPred['kategori_polusi']) : 'Belum ada prediksi' ?></div>
        </div>
    </div>

    <!-- TABS -->
    <div class="tabs">
        <div class="tab-buttons" role="tablist">
            <button class="tab-btn active" data-tab="terkini">Polusi Terkini</button>
            <button class="tab-btn" data-tab="history">History Polusi</button>
            <button class="tab-btn" data-tab="prediksi">Prediksi Polusi</button>
        </div>

        <div class="tab-content" id="tab-terkini">
            <?php if ($latest): ?>
                <table>
                    <tr><th>Waktu</th><th>Suhu (°C)</th><th>Kelembapan (%)</th><th>PM2.5</th><th>Kategori</th></tr>
                    <tr>
                        <td><?= htmlspecialchars($latest['timestamp']) ?></td>
                        <td><?= isset($latest['suhu']) ? number_format($latest['suhu'],1) : '-' ?></td>
                        <td><?= isset($latest['kelembapan']) ? number_format($latest['kelembapan'],0) : '-' ?></td>
                        <td><?= htmlspecialchars($latest['kualitas_udara']) ?></td>
                        <td>
                            <?php if ($latest['kategori_udara']): ?>
                                <span style="padding:6px 10px;border-radius:8px;background:<?= warnaKategori($latest['kategori_udara']) ?>;color:white;font-weight:700;">
                                    <?= htmlspecialchars($latest['kategori_udara']) ?>
                                </span>
                            <?php else: echo '-'; endif; ?>
                        </td>
                    </tr>
                </table>
            <?php else: ?>
                <div class="muted">Tidak ada data terkini untuk lokasi ini.</div>
            <?php endif; ?>
        </div>

        <div class="tab-content" id="tab-history" style="display:none">
            <h4 style="margin:0 0 10px 0">History (30 Terakhir)</h4>
            <?php if (count($historyRows) > 0): ?>
                <table>
                    <tr><th>Tanggal</th><th>Rata-rata Suhu (°C)</th><th>Nilai Polusi (µg/m³)</th></tr>
                    <?php foreach ($historyRows as $h): ?>
                        <tr>
                            <td><?= htmlspecialchars($h['tanggal']) ?></td>
                            <td><?= isset($h['suhu']) ? number_format($h['suhu'],1) : '-' ?></td>
                            <td><?= htmlspecialchars($h['nilai_polusi']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <div class="muted">Tidak ada history untuk lokasi ini.</div>
            <?php endif; ?>
        </div>

        <div class="tab-content" id="tab-prediksi" style="display:none">
            <h4 style="margin:0 0 10px 0">Prediksi (Semua)</h4>
            <?php if (count($prediksiRows) > 0): ?>
                <table>
                    <tr><th>Waktu</th><th>Prediksi (µg/m³)</th><th>Kategori</th></tr>
                    <?php foreach ($prediksiRows as $p): ?>
                        <tr>
                            <td><?= htmlspecialchars($p['waktu']) ?></td>
                            <td><?= htmlspecialchars($p['nilai_polusi']) ?></td>
                            <td>
                                <span style="padding:6px 10px;border-radius:8px;background:<?= warnaKategori($p['kategori_polusi']) ?>;color:white;font-weight:700">
                                    <?= htmlspecialchars($p['kategori_polusi']) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <div class="muted">Belum ada data prediksi untuk lokasi ini.</div>
            <?php endif; ?>
        </div>
    </div>

</div>

<script>
    // Tab switching
    const btns = document.querySelectorAll('.tab-btn');
    const tabs = {
        'terkini': document.getElementById('tab-terkini'),
        'history': document.getElementById('tab-history'),
        'prediksi': document.getElementById('tab-prediksi')
    };

    btns.forEach(b => b.addEventListener('click', function(){
        btns.forEach(x=>x.classList.remove('active'));
        this.classList.add('active');
        const key = this.dataset.tab;
        for (let k in tabs) {
            tabs[k].style.display = (k === key) ? 'block' : 'none';
        }
    }));
</script>
</body>
</html>