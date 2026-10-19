<?php
require_once "koneksi.php";

$user_id = (int)$_SESSION['id'];

$profil = ambil_satu(
    $koneksi,
    "SELECT id, nama, kelas
     FROM siswa
     WHERE user_id = ?",
    "i",
    $user_id
);

$data = [];

if ($profil) {
    $data = ambil_semua(
        $koneksi,
        "SELECT mapel.kode_mapel, mapel.nama_mapel,
                nilai_rapor.nilai_kkm,
                nilai_rapor.nilai_pengetahuan,
                nilai_rapor.nilai_keterampilan,
                nilai_rapor.keterangan
         FROM nilai_rapor
         JOIN mapel ON nilai_rapor.mapel_id = mapel.id
         WHERE nilai_rapor.siswa_id = ?
         ORDER BY mapel.nama_mapel ASC",
        "i",
        $profil['id']
    );
}

require "navbar.php";
?>

<div class="container mt-4">
    <h1 class="h2 mb-2">Nilai Rapor Saya</h1>

    <?php if ($profil): ?>
        <p class="text-muted">
            Nama: <?= e($profil['nama']) ?> |
            Kelas: <?= e($profil['kelas']) ?>
        </p>

        <div class="tabel-bingkai">
            <div class="table-responsive">
                <table class="table table-hover tabel-sip">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Mapel</th>
                            <th>Mata Pelajaran</th>
                            <th>KKM</th>
                            <th>Nilai Pengetahuan</th>
                            <th>Nilai Keterampilan</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($data): ?>
                            <?php foreach ($data as $i => $nilai): ?>
                                <tr>
                                    <td class="tengah"><?= $i + 1 ?></td>
                                    <td><?= e($nilai['kode_mapel']) ?></td>
                                    <td><?= e($nilai['nama_mapel']) ?></td>
                                    <td class="tengah"><?= (int)$nilai['nilai_kkm'] ?></td>
                                    <td class="tengah"><?= (int)$nilai['nilai_pengetahuan'] ?></td>
                                    <td class="tengah"><?= (int)$nilai['nilai_keterampilan'] ?></td>
                                    <td><?= e($nilai['keterangan'] ?? '-') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    Nilai rapor belum tersedia.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">
            Akun ini belum terhubung dengan profil siswa. Hubungi admin.
        </div>
    <?php endif; ?>
</div>

<?php require "footer.php"; ?>