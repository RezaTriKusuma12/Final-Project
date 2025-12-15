<?php
require "../koneksi.php";
require "../app/AnalyticsService.php";


$analytics = new AnalyticsService($koneksi);


header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="analytics_report.csv"');


$output = fopen('php://output', 'w');
fputcsv($output, ['Metric', 'Value']);


fputcsv($output, ['Total Data Udara', $analytics->hitungTotalData()]);
fputcsv($output, ['Jumlah Lokasi Unik', $analytics->hitungLokasiUnik()]);
fputcsv($output, ['Rekomendasi', $analytics->rekomendasiUdara()]);


fclose($output);
exit;