<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Export Data</title>
    <style>
        body { font-family: Arial; padding: 30px; }
        .btn {
            padding: 12px 20px;
            background: green;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            display: inline-block;
            margin: 8px 0;
        }
        .btn:hover { background: #141302ff; }
    </style>
</head>
<body>

<h2>📁 Export Data ke CSV</h2>
<p>Klik tombol berikut untuk export + penyimpanan otomatis ke server:</p>

<a class="btn" href="export/export_data_udara.php">Export Data Udara</a><br>
<a class="btn" href="export/export_history_polusi.php">Export History Polusi</a><br>
<a class="btn" href="export/export_prediksi_polusi.php">Export Prediksi Polusi</a><br>
<a class="btn" href="export/export_user.php">Export User</a><br>

</body>
</html>
