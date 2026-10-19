<?php
require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: daftar_user.php");
    exit;
}

$id       = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
$username = trim($_POST['username'] ?? '');
$pass     = $_POST['password'] ?? '';   // JANGAN di-hash dulu, cek kosong/tidaknya dulu

if ($id === false || $username === '') {
    alert_kembali("Data tidak lengkap, username wajib diisi.");
}

// Role diambil dari database (bukan dari form), supaya tidak bisa dimanipulasi
$user = ambil_satu($koneksi, "SELECT role FROM users WHERE id = ?", "i", $id);
if (!$user) {
    alert_redirect("User tidak ditemukan.", "daftar_user.php");
}
$role = $user['role'];

// Username tidak boleh sama dengan user lain
$duplikat = ambil_satu($koneksi, "SELECT id FROM users WHERE username = ? AND id <> ?", "si", $username, $id);
if ($duplikat) {
    alert_kembali("Username sudah dipakai user lain.");
}

// Validasi data profil sesuai role
if ($role === 'siswa') {
    $nis   = trim($_POST['nis'] ?? '');
    $nama  = trim($_POST['nama'] ?? '');
    $kelas = trim($_POST['kelas'] ?? '');
    $jk    = $_POST['jenis_kelamin_siswa'] ?? '';

    if ($nis === '' || $nama === '' || $kelas === '' || !in_array($jk, ['L', 'P'], true)) {
        alert_kembali("Data profil siswa tidak lengkap.");
    }
} elseif ($role === 'guru') {
    $nip  = trim($_POST['nip'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $jk   = $_POST['jenis_kelamin_guru'] ?? '';

    if ($nip === '' || $nama === '' || !in_array($jk, ['L', 'P'], true)) {
        alert_kembali("Data profil guru tidak lengkap.");
    }
}

// Transaction: tabel users dan tabel profil harus berhasil dua-duanya, atau batal semua
mysqli_begin_transaction($koneksi);

try {
    if ($pass !== '') {
        // password diubah
        $pass_hash = password_hash($pass, PASSWORD_DEFAULT);
        $ok = siapkan($koneksi, "UPDATE users SET username = ?, password = ? WHERE id = ?", "ssi", $username, $pass_hash, $id);
    } else {
        // password tidak diubah
        $ok = siapkan($koneksi, "UPDATE users SET username = ? WHERE id = ?", "si", $username, $id);
    }
    if (!$ok) {
        throw new Exception("Update users gagal.");
    }

    if ($role === 'siswa') {
        $ok = siapkan(
            $koneksi,
            "UPDATE siswa SET nis = ?, nama = ?, kelas = ?, jenis_kelamin = ? WHERE user_id = ?",
            "ssssi",
            $nis, $nama, $kelas, $jk, $id
        );
        if (!$ok) {
            throw new Exception("Update siswa gagal.");
        }
    } elseif ($role === 'guru') {
        $ok = siapkan(
            $koneksi,
            "UPDATE guru SET nip = ?, nama = ?, jenis_kelamin = ? WHERE user_id = ?",
            "sssi",
            $nip, $nama, $jk, $id
        );
        if (!$ok) {
            throw new Exception("Update guru gagal.");
        }
    }
    // role admin: hanya tabel users

    mysqli_commit($koneksi);
} catch (Exception $ex) {
    mysqli_rollback($koneksi);
    alert_kembali("Data gagal diubah. Periksa kembali isian (misalnya NIS/NIP mungkin sudah dipakai).");
}

alert_redirect("Data berhasil diubah", "daftar_user.php");
