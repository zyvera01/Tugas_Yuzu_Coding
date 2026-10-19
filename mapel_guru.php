<?php
require_once "koneksi.php";

$user_id = (int)$_SESSION['id'];

$data = ambil_semua(
    $koneksi,
    "SELECT mapel.id, mapel.kode_mapel, mapel.nama_mapel
     FROM mapel
     JOIN guru ON mapel.guru_id = guru.id
     WHERE guru.user_id = ?
     ORDER BY mapel.nama_mapel ASC",
    "i",
    $user_id
);

require "navbar.php";
?>

<div class="container mt-4">
    <h1 class="h2 mb-3">Mata Pelajaran yang Diampu</h1>
    <p class="text-muted">
        Daftar ini hanya menampilkan mapel yang ditugaskan kepada akun guru kamu.
    </p>

    <div class="tabel-bingkai">
        <div class="table-responsive">
            <table class="table table-hover tabel-sip">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Mapel</th>
                        <th>Nama Mapel</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($data): ?>
                        <?php foreach ($data as $i => $mapel): ?>
                            <tr>
                                <td class="tengah"><?= $i + 1 ?></td>
                                <td><?= e($mapel['kode_mapel']) ?></td>
                                <td><?= e($mapel['nama_mapel']) ?></td>
                                <td class="tengah">
                                    <a href="nilai.php"
                                       class="btn btn-sm btn-primary">
                                        Kelola Nilai
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-4">
                                Belum ada mapel yang ditugaskan kepada akun ini.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require "footer.php"; ?>