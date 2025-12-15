<?php
require "../koneksi.php";

// buat folder backup jika belum ada
$backupDir = "../csv_backup/";
if (!file_exists($backupDir)) {
    mkdir($backupDir, 0777, true);
}

// ambil data
$sql = "SELECT * FROM data_udara ORDER BY timestamp DESC";
$result = mysqli_query($koneksi, $sql);

// nama file
$filename = "data_udara_" . date("Y-m-d_H-i-s") . ".csv";
$filepath = $backupDir . $filename;

// simpan ke server
$file = fopen($filepath, "w");
fputcsv($file, ["id_user", "lokasi", "suhu", "kelembapan", "kategori_udara", "kualitas_udara", "timestamp"]);

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($file, $row);
}
fclose($file);

// download ke user
header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=\"$filename\"");
readfile($filepath);
exit;
