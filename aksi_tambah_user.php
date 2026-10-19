<?php
require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: daftar_user.php");
    exit;
}

$username = trim($_POST['username'] ?? '');
$pass     = $_POST['password'] ?? '';
$role     = $_POST['role'] ?? '';

if ($username === '' || $pass === '' || $role === '') {
    alert_kembali("Data tidak lengkap, silakan isi semua field.");
}

if (!in_array($role, ['admin', 'guru', 'siswa'], true)) {
    alert_kembali("Role tidak valid.");
}

// validasi tambahan sesuai role
if ($role === 'siswa') {
    $nis           = trim($_POST['nis'] ?? '');
    $nama          = trim($_POST['nama'] ?? '');
    $kelas         = trim($_POST['kelas'] ?? '');
    $jenis_kelamin = $_POST['jenis_kelamin_siswa'] ?? '';

    if ($nis === '' || $nama === '' || $kelas === '' || !in_array($jenis_kelamin, ['L', 'P'], true)) {
        alert_kembali("Data profil siswa tidak lengkap.");
    }
}

if ($role === 'guru') {
    $nip                = trim($_POST['nip'] ?? '');
    // Nama diambil dari form tambah guru; kalau kosong (misal dari form pilih role), pakai username
    $nama_guru          = trim($_POST['nama'] ?? '');
    if ($nama_guru === '') {
        $nama_guru = $username;
    }
    $jenis_kelamin_guru = $_POST['jenis_kelamin_guru'] ?? '';

    if ($nip === '' || !in_array($jenis_kelamin_guru, ['L', 'P'], true)) {
        alert_kembali("Data profil guru tidak lengkap.");
    }
}

// Username tidak boleh kembar
if (ambil_satu($koneksi, "SELECT id FROM users WHERE username = ?", "s", $username)) {
    alert_kembali("Username sudah dipakai, silakan pilih username lain.");
}

$pass_hash = password_hash($pass, PASSWORD_DEFAULT);

mysqli_begin_transaction($koneksi);

try {
    // insert ke tabel users (berlaku untuk semua role)
    $sql_users = "INSERT INTO users (username, password, role, created_at) VALUES (?, ?, ?, NOW())";
    $stmt_users = mysqli_prepare($koneksi, $sql_users);
    if (!$stmt_users) {
        throw new Exception("Prepare users gagal.");
    }

    mysqli_stmt_bind_param($stmt_users, "sss", $username, $pass_hash, $role);
    if (!mysqli_stmt_execute($stmt_users)) {
        throw new Exception("Insert users gagal.");
    }

    $id_terakhir = mysqli_insert_id($koneksi);

    // insert tambahan ke tabel siswa, khusus role siswa
    if ($role === 'siswa') {
        $sql_siswa = "INSERT INTO siswa (nis, nama, kelas, jenis_kelamin, user_id) VALUES (?, ?, ?, ?, ?)";
        $stmt_siswa = mysqli_prepare($koneksi, $sql_siswa);
        if (!$stmt_siswa) {
            throw new Exception("Prepare siswa gagal.");
        }

        mysqli_stmt_bind_param($stmt_siswa, "ssssi", $nis, $nama, $kelas, $jenis_kelamin, $id_terakhir);
        if (!mysqli_stmt_execute($stmt_siswa)) {
            throw new Exception("Insert siswa gagal (NIS mungkin sudah terdaftar).");
        }
    }

    // insert tambahan ke tabel guru, khusus role guru
    if ($role === 'guru') {
        $sql_guru = "INSERT INTO guru (nip, nama, jenis_kelamin, user_id) VALUES (?, ?, ?, ?)";
        $stmt_guru = mysqli_prepare($koneksi, $sql_guru);
        if (!$stmt_guru) {
            throw new Exception("Prepare guru gagal.");
        }

        mysqli_stmt_bind_param($stmt_guru, "sssi", $nip, $nama_guru, $jenis_kelamin_guru, $id_terakhir);
        if (!mysqli_stmt_execute($stmt_guru)) {
            throw new Exception("Insert guru gagal (NIP mungkin sudah terdaftar).");
        }
    }

    // role admin: cukup insert ke tabel users saja

    mysqli_commit($koneksi);
} catch (Exception $e) {
    mysqli_rollback($koneksi);
    alert_kembali("Gagal menyimpan data: " . $e->getMessage());
}

alert_redirect("Data berhasil disimpan", "daftar_user.php");
