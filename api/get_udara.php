<?php
header("Content-Type: application/json");
require_once __DIR__ . "/apiclient.php";

$lat = $_GET["lat"] ?? null;
$lon = $_GET["lon"] ?? null;

if (!$lat || !$lon) {
    echo json_encode(["error" => "lat dan lon wajib diisi"]);
    exit;
}

$api = new ApiClient();
$response = $api->getUdara($lat, $lon);

// ============ AMBIL DATA AQI ============
$aqiBlock = $response["aqi"];
$aqiData = $aqiBlock["list"][0] ?? null;

$pm25 = $aqiData["components"]["pm2_5"] ?? null;

// ============ AMBIL DATA CUACA ============
$weatherBlock = $response["weather"];
$temp = $weatherBlock["main"]["temp"] ?? null;
$humidity = $weatherBlock["main"]["humidity"] ?? null;

echo json_encode([
    "pm25" => $pm25,
    "suhu" => $temp,
    "humid" => $humidity,
    "raw" => $response
]);
