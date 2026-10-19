<?php
require_once "koneksi.php";

$user_id = (int)$_SESSION['id'];

$profil = ambil_satu(
    $koneksi,
    "SELECT users.username, users.created_at,
            siswa.nis, siswa.nama, siswa.kelas, siswa.jenis_kelamin
     FROM users
     JOIN siswa ON siswa.user_id = users.id
     WHERE users.id = ? AND users.role = 'siswa'",
    "i",
    $user_id
);

require "navbar.php";
?>

<div class="container mt-4">
    <h1 class="h2 mb-3">Profil Saya</h1>

    <?php if ($profil): ?>
        <div class="card">
            <div class="card-header">Informasi Siswa</div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Nama</div>
                    <div class="col-sm-8"><?= e($profil['nama']) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">NIS</div>
                    <div class="col-sm-8"><?= e($profil['nis']) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Kelas</div>
                    <div class="col-sm-8"><?= e($profil['kelas']) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Jenis Kelamin</div>
                    <div class="col-sm-8">
                        <?= $profil['jenis_kelamin'] === 'L'
                            ? 'Laki-laki' : 'Perempuan' ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted">Username</div>
                    <div class="col-sm-8"><?= e($profil['username']) ?></div>
                </div>
                <div class="row">
                    <div class="col-sm-4 text-muted">Tanggal Akun</div>
                    <div class="col-sm-8"><?= e($profil['created_at']) ?></div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">
            Profil siswa belum terhubung dengan akun ini. Hubungi admin sekolah.
        </div>
    <?php endif; ?>
</div>

<?php require "footer.php"; ?>