<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit;
}

require "../koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $kategori = $_POST['kategori'];
    $min     = $_POST['pm_min'];
    $max     = $_POST['pm_max'];
    $warna   = $_POST['warna'];

    $stmt = $koneksi->prepare("
        INSERT INTO kategori_udara (nama_kategori, pm_min, pm_max, warna)
        VALUES (?, ?, ?, ?)
    ");

    $stmt->bind_param("siis", $kategori, $min, $max, $warna);
    $stmt->execute();

    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Kategori</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 40px;
        }
        .card {
            background: white;
            width: 420px;
            margin: auto;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        }
        .card h2 { text-align: center; margin-bottom: 25px; }
        label { font-weight: bold; }
        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #3498db;
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: bold;
            font-size: 16px;
        }
    </style>

</head>
<body>

<div class="card">
    <h2>Tambah Kategori Udara</h2>

    <form method="POST">

        <label>Kategori</label>
        <input type="text" name="kategori" required>

        <label>PM Min</label>
        <input type="number" name="pm_min" required>

        <label>PM Max</label>
        <input type="number" name="pm_max" required>

        <label>Warna</label>
        <input type="text" name="warna" placeholder="contoh: #FF0000" required>

        <button type="submit">Simpan</button>
    </form>
</div>

</body>
</html>