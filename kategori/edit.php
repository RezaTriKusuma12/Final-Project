<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit;
}

require "../koneksi.php";

// Validasi ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);

// Ambil data kategori berdasarkan ID
$stmt = $koneksi->prepare("SELECT * FROM kategori_udara WHERE id_kategori = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}

// Jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $kategori = $_POST['kategori'];
    $pm_min   = $_POST['pm_min'];
    $pm_max   = $_POST['pm_max'];
    $warna    = $_POST['warna'];

    $stmt2 = $koneksi->prepare("
        UPDATE kategori_udara 
        SET nama_kategori=?, pm_min=?, pm_max=?, warna=? 
        WHERE id_kategori=?
    ");

    $stmt2->bind_param("siisi", $kategori, $pm_min, $pm_max, $warna, $id);
    $stmt2->execute();

    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Kategori</title>

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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
        }

        .card h2 {
            text-align: center;
            margin-bottom: 25px;
            font-weight: bold;
            color: #333;
        }

        label {
            font-weight: bold;
        }

        input, textarea {
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
    <h2>Edit Kategori Udara</h2>

    <a href="index.php" class="back">← Kembali</a>

    <form method="POST">

        <label>Kategori</label>
        <input type="text" name="kategori" value="<?= htmlspecialchars($data['nama_kategori']) ?>" required>

        <label>PM Min</label>
        <input type="number" name="pm_min" value="<?= htmlspecialchars($data['pm_min']) ?>" required>

        <label>PM Max</label>
        <input type="number" name="pm_max" value="<?= htmlspecialchars($data['pm_max']) ?>" required>

        <label>Warna</label>
        <input type="text" name="warna" value="<?= htmlspecialchars($data['warna']) ?>" required>

        <button type="submit">Update</button>
    </form>
</div>

</body>
</html>