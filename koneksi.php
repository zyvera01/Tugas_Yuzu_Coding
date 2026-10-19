<?php
mysqli_report(MYSQLI_REPORT_OFF);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$hostname = "127.0.0.1";
$username = "root";
$password = "root";
$db       = "db_siacad_smk";

$koneksi = mysqli_connect($hostname, $username, $password, $db, 3306);

if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

mysqli_set_charset($koneksi, "utf8mb4");

function e($teks)
{
    return htmlspecialchars((string)$teks, ENT_QUOTES, 'UTF-8');
}

function siapkan($koneksi, $sql, $types = "", ...$params)
{
    $stmt = mysqli_prepare($koneksi, $sql);

    if (!$stmt) {
        return false;
    }

    if ($types !== "") {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        return false;
    }

    return $stmt;
}

function ambil_satu($koneksi, $sql, $types = "", ...$params)
{
    $stmt = siapkan($koneksi, $sql, $types, ...$params);

    if (!$stmt) {
        return null;
    }

    $hasil = mysqli_stmt_get_result($stmt);
    $data = $hasil ? mysqli_fetch_assoc($hasil) : null;

    mysqli_stmt_close($stmt);

    return $data;
}

function ambil_semua($koneksi, $sql, $types = "", ...$params)
{
    $stmt = siapkan($koneksi, $sql, $types, ...$params);

    if (!$stmt) {
        return [];
    }

    $hasil = mysqli_stmt_get_result($stmt);
    $data = $hasil ? mysqli_fetch_all($hasil, MYSQLI_ASSOC) : [];

    mysqli_stmt_close($stmt);

    return $data;
}

function alert_redirect($pesan, $url)
{
    echo "<script>alert(" . json_encode($pesan) .
         "); window.location.href=" . json_encode($url) . ";</script>";
    exit;
}

function alert_kembali($pesan)
{
    echo "<script>alert(" . json_encode($pesan) .
         "); window.history.back();</script>";
    exit;
}

/*
 * PEMERIKSAAN AKSES TERPUSAT
 * Semua halaman yang memakai koneksi.php diperiksa di sini.
 * Admin mendapat akses penuh.
 */
$halaman = basename($_SERVER['PHP_SELF']);

if ($halaman !== 'aksi_login.php') {
    if (empty($_SESSION['login']) || empty($_SESSION['id'])) {
        header("Location: login.php");
        exit;
    }

    $role = isset($_SESSION['role']) ? $_SESSION['role'] : '';

    $akses_role = [
        'guru' => [
            'dashboard.php',
            'daftar_siswa.php',
            'mapel_guru.php',
            'nilai.php',
            'aksi_nilai.php'
        ],
        'siswa' => [
            'dashboard.php',
            'profil_siswa.php',
            'mapel_siswa.php',
            'nilai_siswa.php'
        ]
    ];

    if ($role !== 'admin') {
        if (
            !isset($akses_role[$role]) ||
            !in_array($halaman, $akses_role[$role], true)
        ) {
            http_response_code(403);
            echo '<!doctype html><html lang="id"><head>';
            echo '<meta charset="utf-8">';
            echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
            echo '<title>Akses Ditolak</title>';
            echo '</head><body style="font-family:Arial;padding:30px">';
            echo '<h2>Akses ditolak (403)</h2>';
            echo '<p>Akun kamu tidak memiliki izin membuka halaman ini.</p>';
            echo '<p><a href="dashboard.php">Kembali ke dashboard</a></p>';
            echo '</body></html>';
            exit;
        }
    }
}
?>