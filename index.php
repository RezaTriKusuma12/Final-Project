<?php
session_start();

// proteksi halaman
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

require "koneksi.php";

$nama = $_SESSION["user"]["nama"];


// =========================================================
//                  CLASS NOTIFIKASI UDARA
// =========================================================
class NotifikasiUdara {
    private $db;
    public $pesan = "";
    public $warna = "#e2e3e5";
    public $pm25 = "";
    public $waktu = "";
    public $kategori = "";

    public function __construct($koneksi) {
        $this->db = $koneksi;
    }

    public function ambilDataTerbaru() {
        $q = mysqli_query($this->db, 
            "SELECT kategori_udara, kualitas_udara, timestamp 
             FROM data_udara 
             ORDER BY id_udara DESC 
             LIMIT 1"
        );

        if ($q && mysqli_num_rows($q) > 0) {
            $data = mysqli_fetch_assoc($q);
            $this->kategori = $data['kategori_udara'];
            $this->pm25 = $data['kualitas_udara'];
            $this->waktu = $data['timestamp'];

            $this->prosesNotif();
        } else {
            $this->pesan = "Belum ada data kualitas udara.";
            $this->warna = "#e2e3e5";
        }
    }

    private function prosesNotif() {
        switch ($this->kategori) {
            case "Baik":
                $this->pesan = "Kualitas udara BAIK. Aman untuk beraktivitas 😊";
                $this->warna = "#d4edda";
                break;

            case "Sedang":
                $this->pesan = "Kualitas udara SEDANG 🥺. Tetap waspada ya.";
                $this->warna = "#fff3cd";
                break;

            case "Tidak Sehat":
                $this->pesan = "Kualitas udara TIDAK SEHAT ⚠️. Gunakan masker dan kurangi aktivitas luar!";
                $this->warna = "#f8d7da";
                break;

            case "Sangat Tidak Sehat":
                $this->pesan = "Kualitas udara SANGAT TIDAK SEHAT 🚨. Gunakan masker dan kurangi aktivitas luar!";
                $this->warna = "#f44336";
                break; 
                
            case "Berbahaya ":
                $this->pesan = "Kualitas udara BERBAHAYA 🚷. Gunakan masker dan kurangi aktivitas luar!";
                $this->warna = "#9C27B0";
                break;        

            default:
                $this->pesan = "Status kualitas udara belum diketahui.";
                $this->warna = "#e2e3e5";
                break;
        }
    }
}


// =========================================================
//                       BUAT OBJECT
// =========================================================
$notif = new NotifikasiUdara($koneksi);
$notif->ambilDataTerbaru();

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Realtime Weather</title>

    <!-- Leaflet MAP -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: #f4f8fb;
        }
        .navbar {
            background: #0074D9;
            padding: 12px 25px;
            display: flex;
            align-items: center;
            color: white;
            gap: 25px;
        }
        .navbar img { width: 35px; height: 35px; }
        .navbar a { color: white; text-decoration: none; font-weight: 500; }
        .navbar-title { flex: 1; text-align: center; font-size: 20px; font-weight: bold; }
        .logout-btn {
            background: #FF4136;
            padding: 8px 15px;
            border-radius: 7px;
            text-decoration: none;
            color: white;
        }
        .subheader {
            background: #e0e7ef;
            padding: 8px 0;
            text-align: center;
            font-size: 14px;
            color: #003366;
        }

        .tab-menu {
            display: flex;
            justify-content: center;
            margin-top: 15px;
            gap: 20px;
        }
        .tab-menu a {
            padding: 10px 25px;
            background: #0074D9;
            color: white;
            border-radius: 7px;
            text-decoration: none;
            font-weight: 500;
        }

        .container { padding: 30px; }
        .card-welcome {
            background: white;
            padding: 18px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 25px;
        }

        .menu-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
        }
        .menu-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            border: 2px solid #cfd9e2;
            text-decoration: none;
            color: black;
            transition: 0.25s;
        }
        .menu-card:hover {
            transform: translateY(-4px);
            border-color: #0074D9;
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }

        map {
            margin-top: 30px;
            height: 320px;
            width: 100%;
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        }
    </style>
</head>

<body>

    <div class="navbar">
    <img src="udaraku.jpg" alt="Logo">

    <a href="tentang.php">Tentang</a>

    <div class="navbar-title">
        <span id="waktu-realtime"></span>
    </div>

    <!-- Grup tombol di kanan -->
    <div style="display: flex; align-items: center; gap: 10px;">
        <a href="export_page.php" class="export-btn">Export</a>
        <a href="contact.php" class="contact-btn">Contact</a>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</div>

    <div class="subheader">
        <?php if (!empty($notif->pesan)): ?>
            <div style="
                background: <?= $notif->warna ?>;
                padding: 15px;
                margin: 20px;
                border-left: 6px solid #444;
                border-radius: 8px;
                font-size: 15px;
                color: #000;
                box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            ">
                <b>NOTIFIKASI KUALITAS UDARA</b><br>
                <?= htmlspecialchars($notif->pesan) ?><br>
                <small>
                    PM2.5: <b><?= htmlspecialchars($notif->pm25) ?></b> |
                    Update: <?= htmlspecialchars($notif->waktu) ?>
                </small>
            </div>
        <?php endif; ?>
    </div>

    <div class="tab-menu">
        <a href="index.php">Dashboard</a>
        <a href="detail.php">Detail</a>
    </div>

    <div class="container">

        <div class="card-welcome">
            <h3>Selamat datang, <b><?= $nama ?></b> 👋</h3>
            <p>Mulai monitoring kualitas udara kampus secara realtime.</p>
        </div>

        <div class="menu-container">

            <a href="mapping.php" class="menu-card">
                <h3>🌍 Mapping Udara Kampus</h3>
                <p>Lihat lokasi sensor dan kondisi udara.</p>
            </a>

            <a href="prediksi.php" class="menu-card">
                <h3>🌤 Prediksi Polusi</h3>
                <p>Prediksi Polusi di jam Sibuk.</p>
            </a>

            <a href="kategori.php" class="menu-card">
                <h3>🌤 Kategori Udara</h3>
                <p>Status udara: Baik / Sedang / Tidak Sehat.</p>
            </a>

            <a href="history.php" class="menu-card">
                <h3>🕒 History Cuaca</h3>
                <p>Riwayat & grafik kualitas udara.</p>
            </a>

        </div>

        <div id="map"></div>

    </div>

    <script>
        function updateWaktu() {
            const now = new Date();
            const format = now.toLocaleString("id-ID", {
                weekday: "long",
                year: "numeric",
                month: "long",
                day: "numeric",
                hour: "2-digit",
                minute: "2-digit",
                second: "2-digit"
            });

            document.getElementById("waktu-realtime").innerHTML = format;
        }

        setInterval(updateWaktu, 1000);
        updateWaktu();

        let map = L.map('map').setView([-6.305, 107.305], 11);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
        }).addTo(map);

        L.marker([-6.305, 107.305]).addTo(map)
            .bindPopup("Kabupaten Karawang")
            .openPopup();
    </script>

</body>
</html>