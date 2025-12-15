<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit;
}

require "../koneksi.php";

// ======================
// Validasi ID
// ======================
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php?msg=invalid_id");
    exit;
}

$id = intval($_GET['id']);

// ======================
// Prepare Query
// ======================
$stmt = $koneksi->prepare("DELETE FROM kategori_udara WHERE id_kategori = ?");
if (!$stmt) {
    // Jika prepare gagal
    header("Location: index.php?msg=prepare_failed");
    exit;
}

$stmt->bind_param("i", $id);

// Eksekusi
if ($stmt->execute()) {

    // Cek apakah ada baris yang terhapus
    if ($stmt->affected_rows > 0) {
        header("Location: index.php?msg=deleted");
    } else {
        // Data tidak ditemukan
        header("Location: index.php?msg=not_found");
    }

    exit;

} else {
    // Gagal eksekusi query
    header("Location: index.php?msg=delete_failed");
    exit;
}
