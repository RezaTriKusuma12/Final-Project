<?php
session_start();
if(!isset($_SESSION["user"])){
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Mapping Udara Kampus</title>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
    }

    #header {
        width: 100%;
        padding: 15px;
        background: white;
        border-bottom: 1px solid #ccc;
    }

    #header h2 {
        margin: 0;
        font-size: 26px;
    }

    #header a {
        font-size: 16px;
        color: purple;
        text-decoration: none;
    }

    #content {
        display: flex;
        width: 100%;
        height: calc(100vh - 80px);
    }

    #map { width: 60%; height: 100%; }
    #streetView { width: 40%; height: 100%; border: none; }

    .legend {
        background: white;
        padding: 10px;
        border: 2px solid #ccc;
        border-radius: 8px;
        line-height: 18px;
        font-size: 14px;
    }

    .custom-icon div {
        border-radius: 50%;
        width: 18px;
        height: 18px;
        border: 2px solid white;
    }
</style>
</head>

<body>

<div id="header">
    <h2>🌍 Mapping Udara Kampus</h2> <br>
    <a href="index.php">← Kembali ke Dashboard</a>
</div>

<div id="content">
    <div id="map"></div>
    <iframe id="streetView" allowfullscreen loading="lazy"></iframe>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
// ============================
// Lokasi Gedung Kampus
// ============================
let gedung = [
    { nama: "Gedung A", lat: -6.3239721, lng: 107.3011138 },
    { nama: "Rektorat", lat: -6.3243146, lng: 107.3009321 },
    { nama: "Gedung F", lat: -6.3247285, lng: 107.3006579 },
    { nama: "Gedung B", lat: -6.3242490, lng: 107.3012473 },
    { nama: "Perpustakaan", lat: -6.3244263, lng: 107.3011782 },
    { nama: "Fakultas Farmasi", lat: -6.3241094, lng: 107.3015943 }
];

// ============================
// Peta Utama
// ============================
let map = L.map('map').setView([-6.3241, 107.3011], 18);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 20
}).addTo(map);

// ============================
// Warna PM2.5
// ============================
function getColor(pm25) {
    if (pm25 <= 12) return "green";
    if (pm25 <= 35.4) return "yellow";
    if (pm25 <= 55.4) return "orange";
    if (pm25 <= 150.4) return "red";
    return "purple";
}

// ============================
// Ambil Data dari Backend API
// ============================
async function getData(lat, lng) {
    try {
        // PERBAIKAN FINAL → gunakan lon= (bukan lng=)
        let response = await fetch(`api/get_udara.php?lat=${lat}&lon=${lng}`);
        let json = await response.json();

        if (json.error) {
            console.warn("API error:", json.error);
            return { pm25: 0, suhu: 0, humid: 0 };
        }

        return json;
    } catch (err) {
        console.error("Gagal memuat API:", err);
        return { pm25: 0, suhu: 0, humid: 0 };
    }
}

// ============================
// Tambah Marker ke Map
// ============================
let markers = [];

async function muatMarker() {
    markers.forEach(m => map.removeLayer(m));
    markers = [];

    for (let g of gedung) {

        let data = await getData(g.lat, g.lng);

        let warna = getColor(data.pm25);

        let icon = L.divIcon({
            className: "custom-icon",
            html: `<div style="background:${warna};"></div>`
        });

        let marker = L.marker([g.lat, g.lng], { icon }).addTo(map);

        marker.bindPopup(`
            <b>${g.nama}</b><br>
            PM2.5: <b>${data.pm25}</b> µg/m³<br>
            Suhu: <b>${data.suhu}°C</b><br>
            Kelembapan: <b>${data.humid}%</b>
        `);

        marker.on("click", () => {
    document.getElementById("streetView").src =
        `https://www.google.com/maps/embed?pb=!4v0!6m8!1m7!1sCAoSLEFGMVFpcFB2bFpmUmtZQVZpZXE5a3M4dVhUc2tINWpBdk11Z3dKMW1GbG92!2m2!1d${g.lat}!2d${g.lng}!3f0!4f0!5f1.2`;
});


        markers.push(marker);
    }
}

// Jalankan awal
muatMarker();

// Refresh tiap 5 menit
setInterval(muatMarker, 5 * 60 * 1000);

// ============================
// Legend
// ============================
let legend = L.control({ position: "bottomright" });

legend.onAdd = function () {
    let div = L.DomUtil.create("div", "legend");
    div.innerHTML = "<b>Kualitas Udara (PM2.5)</b><br>";

    let items = [
        { label: "Baik", color: "green" },
        { label: "Sedang", color: "yellow" },
        { label: "Tidak Sehat", color: "orange" },
        { label: "Sangat Tidak Sehat", color: "red" },
        { label: "Berbahaya", color: "purple" }
    ];

    items.forEach(i => {
        div.innerHTML += `
            <i style="background:${i.color};width:16px;height:16px;display:inline-block;border-radius:3px;margin-right:6px;"></i>
            ${i.label}<br>
        `;
    });

    return div;
};

legend.addTo(map);

</script>

</body>
</html>
