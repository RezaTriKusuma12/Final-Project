<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

// if (php_sapi_name() !== 'cli') exit('Akses ditolak');

require "config.php";
require "kirim_email.php";

// SET TIMEZONE MYSQL
mysqli_query($koneksi, "SET time_zone = '+07:00'");

// ===== AMBIL PM2.5 TERBARU DARI DATABASE =====
$qUdara = mysqli_query(
    $koneksi,
    "SELECT kualitas_udara FROM data_udara ORDER BY timestamp DESC LIMIT 1"
);

$dataUdara = mysqli_fetch_assoc($qUdara);
if (!$dataUdara) {
    die("Data PM2.5 belum tersedia");
}

$pm25 = $dataUdara["kualitas_udara"];
$batas = 55;

// ===== AMBIL STATUS TERAKHIR =====
$qStatus = mysqli_query(
    $koneksi,
    "SELECT status FROM pm25_status WHERE id=1"
);
$statusRow = mysqli_fetch_assoc($qStatus);
$statusTerakhir = $statusRow["status"] ?? "AMAN";

// ===== LOGIKA STATUS (ANTI SPAM) =====
if ($pm25 > $batas && $statusTerakhir === "AMAN") {

    // AMAN → BAHAYA
    kirimEmailBahaya($pm25);
    mysqli_query(
        $koneksi,
        "UPDATE pm25_status SET status='BAHAYA' WHERE id=1"
    );

    echo "EMAIL BAHAYA TERKIRIM | PM2.5: $pm25";

} elseif ($pm25 <= $batas && $statusTerakhir === "BAHAYA") {

    // BAHAYA → AMAN
    kirimEmailAman($pm25);
    mysqli_query(
        $koneksi,
        "UPDATE pm25_status SET status='AMAN' WHERE id=1"
    );

    echo "EMAIL AMAN TERKIRIM | PM2.5: $pm25";

} else {

    echo "STATUS TETAP: $statusTerakhir | PM2.5: $pm25";
}
