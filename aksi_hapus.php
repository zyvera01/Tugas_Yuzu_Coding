<?php
require_once "koneksi.php";

// Hapus sekarang lewat POST (bukan link GET), supaya tidak terhapus hanya karena link dibuka
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: daftar_user.php");
    exit;
}

// Halaman asal tombol Hapus (hanya yang ada di daftar putih)
$kembali = in_array($_POST['kembali'] ?? '', ['daftar_siswa.php', 'daftar_guru.php', 'daftar_user.php'], true)
    ? $_POST['kembali']
    : 'daftar_user.php';

$id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);

if ($id === false) {
    alert_redirect("ID tidak valid.", $kembali);
}

// cek dulu role dari user yang mau dihapus
$user = ambil_satu($koneksi, "SELECT role FROM users WHERE id = ?", "i", $id);

if (!$user) {
    alert_redirect("User tidak ditemukan.", $kembali);
}

$role = $user['role'];

// Cek aturan sebelum menghapus
if ($role === 'guru') {
    $dipakai = ambil_satu(
        $koneksi,
        "SELECT COUNT(*) AS jml FROM mapel WHERE guru_id IN (SELECT id FROM guru WHERE user_id = ?)",
        "i",
        $id
    );
    if ($dipakai && $dipakai['jml'] > 0) {
        alert_redirect(
            "Guru ini masih menjadi pengampu " . $dipakai['jml'] . " mapel. Ganti guru pengampu mapel tersebut dulu.",
            $kembali
        );
    }
} elseif ($role === 'admin') {
    $jumlah = ambil_satu($koneksi, "SELECT COUNT(*) AS jml FROM users WHERE role = 'admin'");
    if ($jumlah && $jumlah['jml'] <= 1) {
        alert_redirect("Admin terakhir tidak boleh dihapus.", $kembali);
    }
}

// Hapus data profil + akun dalam satu transaction
mysqli_begin_transaction($koneksi);

try {
    if ($role === 'siswa') {
        if (!siapkan($koneksi, "DELETE FROM siswa WHERE user_id = ?", "i", $id)) {
            throw new Exception("Hapus siswa gagal.");
        }
    } elseif ($role === 'guru') {
        if (!siapkan($koneksi, "DELETE FROM guru WHERE user_id = ?", "i", $id)) {
            throw new Exception("Hapus guru gagal.");
        }
    }

    if (!siapkan($koneksi, "DELETE FROM users WHERE id = ?", "i", $id)) {
        throw new Exception("Hapus users gagal.");
    }

    mysqli_commit($koneksi);
} catch (Exception $ex) {
    mysqli_rollback($koneksi);
    alert_redirect("Gagal menghapus user. Data mungkin masih dipakai oleh data lain.", $kembali);
}

alert_redirect("User berhasil dihapus", $kembali);
