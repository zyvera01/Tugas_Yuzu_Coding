<?php
require_once "koneksi.php";

$data = ambil_semua(
    $koneksi,
    "SELECT mapel.kode_mapel, mapel.nama_mapel, guru.nama AS nama_guru
     FROM mapel
     LEFT JOIN guru ON mapel.guru_id = guru.id
     ORDER BY mapel.nama_mapel ASC"
);

require "navbar.php";
?>

<div class="container mt-4">
    <h1 class="h2 mb-3">Daftar Mata Pelajaran</h1>

    <div class="tabel-bingkai">
        <div class="table-responsive">
            <table class="table table-hover tabel-sip">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Mapel</th>
                        <th>Nama Mapel</th>
                        <th>Guru Pengampu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($data): ?>
                        <?php foreach ($data as $i => $mapel): ?>
                            <tr>
                                <td class="tengah"><?= $i + 1 ?></td>
                                <td><?= e($mapel['kode_mapel']) ?></td>
                                <td><?= e($mapel['nama_mapel']) ?></td>
                                <td><?= e($mapel['nama_guru'] ?? 'Belum ditentukan') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-4">
                                Belum ada mata pelajaran.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require "footer.php"; ?>