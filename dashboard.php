<?php
require_once "koneksi.php";

$role = $_SESSION['role'];
$user_id = (int)$_SESSION['id'];
$jumlah_siswa = 0;
$jumlah_guru = 0;
$jumlah_mapel = 0;
$jumlah_nilai = 0;

if ($role === 'admin') {
    $jumlah_siswa = (int)(ambil_satu(
        $koneksi, "SELECT COUNT(*) AS total FROM siswa"
    )['total'] ?? 0);

    $jumlah_guru = (int)(ambil_satu(
        $koneksi, "SELECT COUNT(*) AS total FROM guru"
    )['total'] ?? 0);

    $jumlah_mapel = (int)(ambil_satu(
        $koneksi, "SELECT COUNT(*) AS total FROM mapel"
    )['total'] ?? 0);

    $jumlah_nilai = (int)(ambil_satu(
        $koneksi, "SELECT COUNT(*) AS total FROM nilai_rapor"
    )['total'] ?? 0);

} elseif ($role === 'guru') {
    $jumlah_siswa = (int)(ambil_satu(
        $koneksi, "SELECT COUNT(*) AS total FROM siswa"
    )['total'] ?? 0);

    $jumlah_mapel = (int)(ambil_satu(
        $koneksi,
        "SELECT COUNT(*) AS total
         FROM mapel
         JOIN guru ON mapel.guru_id = guru.id
         WHERE guru.user_id = ?",
        "i", $user_id
    )['total'] ?? 0);

    $jumlah_nilai = (int)(ambil_satu(
        $koneksi,
        "SELECT COUNT(*) AS total
         FROM nilai_rapor
         JOIN mapel ON nilai_rapor.mapel_id = mapel.id
         JOIN guru ON mapel.guru_id = guru.id
         WHERE guru.user_id = ?",
        "i", $user_id
    )['total'] ?? 0);

} elseif ($role === 'siswa') {
    $jumlah_mapel = (int)(ambil_satu(
        $koneksi, "SELECT COUNT(*) AS total FROM mapel"
    )['total'] ?? 0);

    $jumlah_nilai = (int)(ambil_satu(
        $koneksi,
        "SELECT COUNT(*) AS total
         FROM nilai_rapor
         JOIN siswa ON nilai_rapor.siswa_id = siswa.id
         WHERE siswa.user_id = ?",
        "i", $user_id
    )['total'] ?? 0);
}

require "navbar.php";
?>

<div class="container mt-4">
    <div class="p-4 mb-4 bg-body-tertiary rounded-3">
        <h1 class="h2">Dashboard SIPN</h1>
        <p class="mb-1">
            Selamat datang, <strong><?= e($_SESSION['nama']) ?></strong>.
        </p>
        <p class="text-muted mb-0">
            Kamu login sebagai <?= e($role) ?>.
        </p>
    </div>

    <div class="row g-3">
        <?php if ($role === 'admin' || $role === 'guru'): ?>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5>Data Siswa</h5>
                        <p class="display-6"><?= $jumlah_siswa ?></p>
                        <a href="daftar_siswa.php" class="btn btn-outline-primary">
                            Lihat siswa
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($role === 'admin'): ?>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5>Data Guru</h5>
                        <p class="display-6"><?= $jumlah_guru ?></p>
                        <a href="daftar_guru.php" class="btn btn-outline-primary">
                            Lihat guru
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5><?= $role === 'guru' ? 'Mapel Diampu' : 'Mata Pelajaran' ?></h5>
                    <p class="display-6"><?= $jumlah_mapel ?></p>
                    <?php if ($role === 'admin'): ?>
                        <a href="mapel.php" class="btn btn-outline-primary">Lihat mapel</a>
                    <?php elseif ($role === 'guru'): ?>
                        <a href="mapel_guru.php" class="btn btn-outline-primary">Lihat mapel</a>
                    <?php else: ?>
                        <a href="mapel_siswa.php" class="btn btn-outline-primary">Lihat mapel</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5><?= $role === 'siswa' ? 'Nilai Rapor Saya' : 'Data Nilai' ?></h5>
                    <p class="display-6"><?= $jumlah_nilai ?></p>
                    <?php if ($role === 'siswa'): ?>
                        <a href="nilai_siswa.php" class="btn btn-outline-primary">Lihat rapor</a>
                    <?php else: ?>
                        <a href="nilai.php" class="btn btn-outline-primary">Kelola nilai</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if ($role === 'siswa'): ?>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5>Profil Saya</h5>
                        <p>Periksa data profil akun siswa kamu.</p>
                        <a href="profil_siswa.php" class="btn btn-outline-primary">
                            Lihat profil
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($role === 'admin'): ?>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5>Akun Pengguna</h5>
                        <p>Kelola akun admin, guru, dan siswa.</p>
                        <a href="daftar_user.php" class="btn btn-outline-primary">
                            Kelola akun
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require "footer.php"; ?>