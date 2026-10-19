<?php
require_once "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: nilai.php");
    exit;
}

$role = $_SESSION['role'];
$user_id = (int)$_SESSION['id'];
$aksi = $_POST['aksi'] ?? '';

/*
 * Admin boleh mengakses semua mapel.
 * Guru hanya boleh mengakses mapel yang terhubung
 * dengan profil guru milik akun yang sedang login.
 */
function mapel_boleh_dikelola($koneksi, $mapel_id, $role, $user_id)
{
    $hasil = ambil_satu(
        $koneksi,
        "SELECT m.id
         FROM mapel m
         WHERE m.id = ?
           AND (
               ? = 'admin'
               OR m.guru_id = (
                   SELECT g.id FROM guru g WHERE g.user_id = ?
               )
           )",
        "isi",
        $mapel_id,
        $role,
        $user_id
    );

    return $hasil !== null;
}

/* Memeriksa apakah nilai lama masih dalam cakupan akses akun ini. */
function nilai_boleh_dikelola($koneksi, $nilai_id, $role, $user_id)
{
    $hasil = ambil_satu(
        $koneksi,
        "SELECT nr.id
         FROM nilai_rapor nr
         JOIN mapel m ON nr.mapel_id = m.id
         WHERE nr.id = ?
           AND (
               ? = 'admin'
               OR m.guru_id = (
                   SELECT g.id FROM guru g WHERE g.user_id = ?
               )
           )",
        "isi",
        $nilai_id,
        $role,
        $user_id
    );

    return $hasil !== null;
}

if ($aksi === 'hapus') {
    $id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);

    if (!$id || !nilai_boleh_dikelola($koneksi, $id, $role, $user_id)) {
        alert_redirect(
            "Nilai tidak ditemukan atau bukan wewenang akun kamu.",
            "nilai.php"
        );
    }

    if (siapkan($koneksi, "DELETE FROM nilai_rapor WHERE id = ?", "i", $id)) {
        alert_redirect("Nilai berhasil dihapus.", "nilai.php");
    }

    alert_redirect("Gagal menghapus nilai.", "nilai.php");
}

if (!in_array($aksi, ['tambah', 'edit'], true)) {
    alert_redirect("Aksi tidak dikenal.", "nilai.php");
}

$siswa_id = filter_var($_POST['siswa_id'] ?? '', FILTER_VALIDATE_INT);
$mapel_id = filter_var($_POST['mapel_id'] ?? '', FILTER_VALIDATE_INT);
$nilai_kkm = filter_var($_POST['nilai_kkm'] ?? '', FILTER_VALIDATE_INT);
$nilai_pengetahuan = filter_var(
    $_POST['nilai_pengetahuan'] ?? '',
    FILTER_VALIDATE_INT
);
$nilai_keterampilan = filter_var(
    $_POST['nilai_keterampilan'] ?? '',
    FILTER_VALIDATE_INT
);
$keterangan = trim($_POST['keterangan'] ?? '');

if (
    $siswa_id === false || $siswa_id === null ||
    $mapel_id === false || $mapel_id === null ||
    $nilai_kkm === false || $nilai_kkm === null ||
    $nilai_pengetahuan === false || $nilai_pengetahuan === null ||
    $nilai_keterampilan === false || $nilai_keterampilan === null
) {
    alert_redirect("Semua kolom nilai wajib diisi dengan angka yang valid.", "nilai.php");
}

if (
    $nilai_kkm < 0 || $nilai_kkm > 100 ||
    $nilai_pengetahuan < 0 || $nilai_pengetahuan > 100 ||
    $nilai_keterampilan < 0 || $nilai_keterampilan > 100
) {
    alert_redirect("Nilai harus berada pada rentang 0 sampai 100.", "nilai.php");
}

if (strlen($keterangan) > 50) {
    alert_redirect("Keterangan maksimal 50 karakter.", "nilai.php");
}

$siswa_ada = ambil_satu(
    $koneksi,
    "SELECT id FROM siswa WHERE id = ?",
    "i",
    $siswa_id
);

if (!$siswa_ada) {
    alert_redirect("Siswa tidak ditemukan.", "nilai.php");
}

if (!mapel_boleh_dikelola($koneksi, $mapel_id, $role, $user_id)) {
    alert_redirect(
        "Kamu tidak memiliki izin mengelola nilai untuk mapel tersebut.",
        "nilai.php"
    );
}

if ($aksi === 'tambah') {
    $ok = siapkan(
        $koneksi,
        "INSERT INTO nilai_rapor
            (siswa_id, mapel_id, nilai_kkm,
             nilai_pengetahuan, nilai_keterampilan, keterangan)
         VALUES (?, ?, ?, ?, ?, ?)",
        "iiiiis",
        $siswa_id,
        $mapel_id,
        $nilai_kkm,
        $nilai_pengetahuan,
        $nilai_keterampilan,
        $keterangan
    );

    if ($ok) {
        alert_redirect("Nilai berhasil ditambahkan.", "nilai.php");
    }

    alert_redirect("Gagal menambahkan nilai.", "nilai.php");
}

$id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);

if (!$id || !nilai_boleh_dikelola($koneksi, $id, $role, $user_id)) {
    alert_redirect(
        "Nilai yang ingin diubah tidak ditemukan atau bukan wewenang akun kamu.",
        "nilai.php"
    );
}

$ok = siapkan(
    $koneksi,
    "UPDATE nilai_rapor
     SET siswa_id = ?, mapel_id = ?, nilai_kkm = ?,
         nilai_pengetahuan = ?, nilai_keterampilan = ?, keterangan = ?
     WHERE id = ?",
    "iiiiisi",
    $siswa_id,
    $mapel_id,
    $nilai_kkm,
    $nilai_pengetahuan,
    $nilai_keterampilan,
    $keterangan,
    $id
);

if ($ok) {
    alert_redirect("Nilai berhasil diperbarui.", "nilai.php");
}

alert_redirect("Gagal memperbarui nilai.", "nilai.php");