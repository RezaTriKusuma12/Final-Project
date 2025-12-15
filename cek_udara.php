<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require __DIR__ . "/vendor/autoload.php";

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Ambil API KEY dari .env
$apiKey = $_ENV["OPENWEATHER_KEY"] ?? null;

if (!$apiKey) {
    die("API Key tidak ditemukan. Pastikan file .env berisi OPENWEATHER_KEY=...");
}

$lat = -6.3239;
$lon = 106.9896;

// URL API
$url = "https://api.openweathermap.org/data/2.5/air_pollution?lat={$lat}&lon={$lon}&appid={$apiKey}";

// Ambil data dari API
$response = @file_get_contents($url);

if ($response === FALSE) {
    die("Gagal mengambil data API OpenWeather. Kemungkinan API KEY salah atau tidak aktif.");
}

$data = json_decode($response, true);

// Validasi data
if (!isset($data["list"][0]["components"]["pm2_5"])) {
    die("Respons API tidak sesuai. Cek API Key atau koordinat.");
}

$pm25 = $data["list"][0]["components"]["pm2_5"];

echo "PM2.5 sekarang: " . $pm25 . "<br>";

if ($pm25 > 55) {
    echo "PM2.5 tinggi! Mengirim email...";
// include kirim_email.php dll
} else {
    echo "PM2.5 masih aman. Tidak mengirim email.";
}
?>
