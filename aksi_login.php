<?php
require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

$username = trim($_POST['username'] ?? '');
$pass = $_POST['password'] ?? '';

if ($username === '' || $pass === '') {
    alert_redirect("Username dan password wajib diisi.", "login.php");
}

$user = ambil_satu(
    $koneksi,
    "SELECT users.id, users.username, users.password, users.role,
            COALESCE(siswa.nama, guru.nama) AS nama,
            siswa.kelas
     FROM users
     LEFT JOIN siswa ON users.id = siswa.user_id
     LEFT JOIN guru ON users.id = guru.user_id
     WHERE users.username = ?",
    "s",
    $username
);

if ($user && password_verify($pass, $user['password'])) {
    session_regenerate_id(true);

    $_SESSION['id'] = (int)$user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['nama'] = $user['nama'] ?: $user['username'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['kelas'] = $user['kelas'] ?? '';
    $_SESSION['login'] = true;

    header("Location: dashboard.php");
    exit;
}

alert_redirect("Username atau password salah!", "login.php");