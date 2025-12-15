<?php
require "../koneksi.php";

$backupDir = "../csv_backup/";
if (!file_exists($backupDir)) mkdir($backupDir, 0777, true);

$sql = "SELECT * FROM prediksi_polusi ORDER BY waktu DESC";
$result = mysqli_query($koneksi, $sql);

$filename = "prediksi_polusi_" . date("Y-m-d_H-i-s") . ".csv";
$filepath = $backupDir . $filename;

$file = fopen($filepath, "w");
fputcsv($file, ["id_user", "waktu", "nilai_polusi", "kategori_polusi", "lokasi"]);

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($file, $row);
}
fclose($file);

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=\"$filename\"");
readfile($filepath);
exit;
