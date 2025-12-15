<?php
class AnalyticsService {
private $db;


public function __construct($db)
{
$this->db = $db;
}


public function hitungTotalData() {
$q = $this->db->query("SELECT COUNT(*) AS total FROM data_udara");
return $q->fetch_assoc()['total'];
}


public function hitungLokasiUnik() {
$q = $this->db->query("SELECT COUNT(DISTINCT lokasi) AS unik FROM data_udara");
return $q->fetch_assoc()['unik'];
}


public function rekomendasiUdara() {
$q = $this->db->query("SELECT AVG(aqi) as avg_aqi FROM data_udara");
$avg = $q->fetch_assoc()['avg_aqi'];


if ($avg < 50) return "Kualitas udara sangat baik, aman untuk aktivitas luar ruangan.";
if ($avg < 100) return "Udara cukup baik. Orang sensitif perlu berhati-hati.";
return "Kualitas udara buruk, kurangi aktivitas luar ruangan.";
}
}