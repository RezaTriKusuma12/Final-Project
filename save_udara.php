<?php
date_default_timezone_set("Asia/Jakarta");

error_reporting(E_ALL);
ini_set("display_errors", 1);

require "koneksi.php";

// SET TIMEZONE MYSQL
mysqli_query($koneksi, "SET time_zone = '+07:00'");

require __DIR__ . "/vendor/autoload.php";
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad(); // lebih aman untuk cron

$API_KEY = $_ENV["OPENWEATHER_KEY"];


$gedung = [
    ["nama" => "Gedung A", "lat" => -6.3239721, "lng" => 107.3011138],
    ["nama" => "Rektorat", "lat" => -6.3243146, "lng" => 107.3009321],
    ["nama" => "Gedung F", "lat" => -6.3247285, "lng" => 107.3006579],
    ["nama" => "Gedung B", "lat" => -6.3242490, "lng" => 107.3012473],
    ["nama" => "Perpustakaan", "lat" => -6.3244263, "lng" => 107.3011782],
    ["nama" => "Fakultas Farmasi", "lat" => -6.3241094, "lng" => 107.3015943]
];

/* ===========================
   FUNGSI AMBIL KATEGORI
=========================== */
function getKategori($pm25, $koneksi) {
    $pm25 = floatval($pm25);

    $query = mysqli_query(
        $koneksi,
        "SELECT nama_kategori 
         FROM kategori_udara 
         WHERE $pm25 BETWEEN pm_min AND pm_max
         LIMIT 1"
    );

    if (mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
        return $row["nama_kategori"];
    }

    return "Tidak diketahui";
}

/* ===========================
   LOOP SETIAP GEDUNG
=========================== */
foreach ($gedung as $g) {

    /* 1. GET PM2.5 */
    $url_polusi = "https://api.openweathermap.org/data/2.5/air_pollution?lat={$g['lat']}&lon={$g['lng']}&appid=$API_KEY";
    $resp_polusi = file_get_contents($url_polusi);
    if (!$resp_polusi) continue;

    $data_polusi = json_decode($resp_polusi, true);
    $pm25 = $data_polusi["list"][0]["components"]["pm2_5"] ?? 0;

    /* 2. GET KATEGORI */
    $kategori = getKategori($pm25, $koneksi);

    /* 3. GET CUACA */
    $url_weather = "https://api.openweathermap.org/data/2.5/weather?lat={$g['lat']}&lon={$g['lng']}&units=metric&appid=$API_KEY";
    $resp_weather = file_get_contents($url_weather);
    if (!$resp_weather) continue;

    $data_weather = json_decode($resp_weather, true);
    $suhu = $data_weather["main"]["temp"] ?? 0;
    $humidity = $data_weather["main"]["humidity"] ?? 0;

    $lokasi = $g["nama"];

    /* 4. SIMPAN KE data_udara */
    mysqli_query($koneksi, "
        INSERT INTO data_udara 
        (id_user, lokasi, suhu, kelembapan, kategori_udara, kualitas_udara, timestamp)
        VALUES (
            1, 
            '$lokasi', 
            '$suhu', 
            '$humidity', 
            '$kategori', 
            '$pm25', 
            NOW()
        )
    ");

    /* 5. SIMPAN history_polusi */
    mysqli_query($koneksi, "
        INSERT INTO history_polusi (id_user, tanggal, lokasi, suhu, nilai_polusi)
        VALUES (1, NOW(), '$lokasi', '$suhu', '$pm25' )
    ");
}

echo "DATA BERHASIL DISIMPAN";