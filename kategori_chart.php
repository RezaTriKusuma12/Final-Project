<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require "koneksi.php";


$query = $koneksi->query("SELECT lokasi, COUNT(*) AS jumlah FROM data_udara GROUP BY lokasi");
$data = [];
while ($row = $query->fetch_assoc()) {
$data[] = $row;
}


echo json_encode($data);