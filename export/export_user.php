<?php
require "../koneksi.php";

$backupDir = "../csv_backup/";
if (!file_exists($backupDir)) mkdir($backupDir, 0777, true);

$sql = "SELECT * FROM user ORDER BY created_at DESC";
$result = mysqli_query($koneksi, $sql);

$filename = "user_" . date("Y-m-d_H-i-s") . ".csv";
$filepath = $backupDir . $filename;

$file = fopen($filepath, "w");
fputcsv($file, ["id_user", "username", "password", "nama", "created_at"]);

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($file, $row);
}
fclose($file);

// unduh
header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=\"$filename\"");
readfile($filepath);
exit;
