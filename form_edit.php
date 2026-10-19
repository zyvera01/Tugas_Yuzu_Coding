<?php
require_once "koneksi.php";

$id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);

if ($id === false) {
    die("ID tidak valid.");
}

$user = ambil_satu($koneksi, "SELECT role FROM users WHERE id = ?", "i", $id);

if (!$user) {
    die("User tidak ditemukan.");
}

switch ($user['role']) {
    case 'admin':
        header("Location: edit_admin.php?id=$id");
        break;
    case 'siswa':
        header("Location: edit_siswa.php?id=$id");
        break;
    case 'guru':
        header("Location: edit_guru.php?id=$id");
        break;
    default:
        die("Role tidak dikenali.");
}
exit;
