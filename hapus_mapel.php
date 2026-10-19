<?php
require_once 'koneksi.php';

// Hapus lewat POST (dikirim dari tombol Hapus di mapel.php)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: mapel.php");
    exit();
}

$id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);

if ($id === false) {
    alert_redirect("ID tidak valid.", "mapel.php");
}

if (siapkan($koneksi, "DELETE FROM mapel WHERE id = ?", "i", $id)) {
    alert_redirect("Data mapel berhasil dihapus!", "mapel.php");
} else {
    alert_redirect("Gagal menghapus mapel. Data mungkin masih dipakai oleh data lain (misalnya nilai).", "mapel.php");
}
