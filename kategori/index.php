<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require "../koneksi.php";

// Cek login
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit;
}

// Ambil data kategori
$result = $koneksi->query("SELECT * FROM kategori_udara ORDER BY id_kategori DESC");
?>

<?php include "../layouts/header.php"; ?>

<style>
    body {
        background: #f4f6f9;
    }

    .card-table {
        background: #fff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-top: 30px;
    }

    th {
        background: #3498db;
        color: white;
        text-align: center;
    }

    td {
        vertical-align: middle !important;
    }
</style>

<div class="container">
    <div class="card-table">
        <h2>Data Kategori Udara</h2>

        <a href="tambah.php" class="btn btn-primary mb-3">Tambah Kategori</a>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="50">#</th>
                    <th width="200">Nama Kategori</th>
                    <th width="100">PM Min</th>
                    <th width="100">PM Max</th>
                    <th width="120">Warna</th>
                    <th width="150">Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                    <td class="text-center"><?= htmlspecialchars($row['pm_min']) ?></td>
                    <td class="text-center"><?= htmlspecialchars($row['pm_max']) ?></td>
                    <td class="text-center">
                        <div style="
                            width: 30px; 
                            height: 20px; 
                            border-radius:4px;
                            background: <?= htmlspecialchars($row['warna']) ?>;">
                        </div>
                        <small><?= htmlspecialchars($row['warna']) ?></small>
                    </td>

                    <td class="text-center">
                        <a href="edit.php?id=<?= $row['id_kategori'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="hapus.php?id=<?= $row['id_kategori'] ?>"
                           onclick="return confirm('Hapus kategori ini?')"
                           class="btn btn-danger btn-sm">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "../layouts/footer.php"; ?>