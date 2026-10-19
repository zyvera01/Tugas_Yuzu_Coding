<?php
require_once "koneksi.php";

$role = $_SESSION['role'];
$user_id = (int)$_SESSION['id'];

$nilai_edit = null;
$error_id = false;

if (isset($_GET['id'])) {
    $id_edit = filter_var($_GET['id'], FILTER_VALIDATE_INT);

    if ($id_edit === false || $id_edit === null) {
        alert_redirect("ID nilai tidak valid.", "nilai.php");
    }

    $nilai_edit = ambil_satu(
        $koneksi,
        "SELECT nr.*
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
        $id_edit,
        $role,
        $user_id
    );

    if (!$nilai_edit) {
        alert_redirect("Nilai tidak ditemukan atau bukan wewenang akun kamu.", "nilai.php");
    }
}

if ($role === 'admin') {
    $daftar_mapel = ambil_semua(
        $koneksi,
        "SELECT id, kode_mapel, nama_mapel
         FROM mapel
         ORDER BY nama_mapel ASC"
    );
} else {
    $daftar_mapel = ambil_semua(
        $koneksi,
        "SELECT m.id, m.kode_mapel, m.nama_mapel
         FROM mapel m
         JOIN guru g ON m.guru_id = g.id
         WHERE g.user_id = ?
         ORDER BY m.nama_mapel ASC",
        "i",
        $user_id
    );
}

$daftar_siswa = ambil_semua(
    $koneksi,
    "SELECT id, nis, nama, kelas
     FROM siswa
     ORDER BY nama ASC"
);

if ($role === 'admin') {
    $data_nilai = ambil_semua(
        $koneksi,
        "SELECT nr.id, nr.nilai_kkm, nr.nilai_pengetahuan,
                nr.nilai_keterampilan, nr.keterangan,
                s.nis, s.nama AS nama_siswa, s.kelas,
                m.kode_mapel, m.nama_mapel
         FROM nilai_rapor nr
         JOIN siswa s ON nr.siswa_id = s.id
         JOIN mapel m ON nr.mapel_id = m.id
         ORDER BY s.nama ASC, m.nama_mapel ASC"
    );
} else {
    $data_nilai = ambil_semua(
        $koneksi,
        "SELECT nr.id, nr.nilai_kkm, nr.nilai_pengetahuan,
                nr.nilai_keterampilan, nr.keterangan,
                s.nis, s.nama AS nama_siswa, s.kelas,
                m.kode_mapel, m.nama_mapel
         FROM nilai_rapor nr
         JOIN siswa s ON nr.siswa_id = s.id
         JOIN mapel m ON nr.mapel_id = m.id
         JOIN guru g ON m.guru_id = g.id
         WHERE g.user_id = ?
         ORDER BY s.nama ASC, m.nama_mapel ASC",
        "i",
        $user_id
    );
}

require "navbar.php";
?>

<div class="container mt-4 mb-5">
    <h1 class="h2 mb-2">Pengelolaan Nilai</h1>
    <p class="text-muted">
        <?php if ($role === 'admin'): ?>
            Admin dapat mengelola nilai semua mata pelajaran.
        <?php else: ?>
            Guru hanya dapat mengelola nilai pada mata pelajaran yang diampu.
        <?php endif; ?>
    </p>

    <div class="card mb-4">
        <div class="card-header">
            <?= $nilai_edit ? 'Edit Nilai Rapor' : 'Tambah Nilai Rapor' ?>
        </div>
        <div class="card-body">
            <?php if (!$daftar_mapel): ?>
                <div class="alert alert-warning">
                    Belum ada mata pelajaran yang tersedia untuk akun ini.
                    <?php if ($role === 'admin'): ?>
                        Tambahkan mapel terlebih dahulu.
                    <?php else: ?>
                        Hubungi admin untuk mengatur mapel yang kamu ampu.
                    <?php endif; ?>
                </div>
            <?php elseif (!$daftar_siswa): ?>
                <div class="alert alert-warning">
                    Belum ada data siswa untuk diberi nilai.
                </div>
            <?php else: ?>
                <form action="aksi_nilai.php" method="POST">
                    <input type="hidden" name="aksi"
                           value="<?= $nilai_edit ? 'edit' : 'tambah' ?>">

                    <?php if ($nilai_edit): ?>
                        <input type="hidden" name="id"
                               value="<?= (int)$nilai_edit['id'] ?>">
                    <?php endif; ?>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Siswa</label>
                            <select name="siswa_id" class="form-select" required>
                                <option value="">-- Pilih Siswa --</option>
                                <?php foreach ($daftar_siswa as $siswa): ?>
                                    <option value="<?= (int)$siswa['id'] ?>"
                                        <?= $nilai_edit && (int)$nilai_edit['siswa_id'] === (int)$siswa['id']
                                            ? 'selected' : '' ?>>
                                        <?= e($siswa['nama']) ?>
                                        (<?= e($siswa['nis']) ?>, kelas <?= e($siswa['kelas']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Mata Pelajaran</label>
                            <select name="mapel_id" class="form-select" required>
                                <option value="">-- Pilih Mapel --</option>
                                <?php foreach ($daftar_mapel as $mapel): ?>
                                    <option value="<?= (int)$mapel['id'] ?>"
                                        <?= $nilai_edit && (int)$nilai_edit['mapel_id'] === (int)$mapel['id']
                                            ? 'selected' : '' ?>>
                                        <?= e($mapel['kode_mapel']) ?> -
                                        <?= e($mapel['nama_mapel']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">KKM</label>
                            <input type="number" name="nilai_kkm"
                                   class="form-control" min="0" max="100" required
                                   value="<?= $nilai_edit ? (int)$nilai_edit['nilai_kkm'] : '75' ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Nilai Pengetahuan</label>
                            <input type="number" name="nilai_pengetahuan"
                                   class="form-control" min="0" max="100" required
                                   value="<?= $nilai_edit ? (int)$nilai_edit['nilai_pengetahuan'] : '' ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Nilai Keterampilan</label>
                            <input type="number" name="nilai_keterampilan"
                                   class="form-control" min="0" max="100" required
                                   value="<?= $nilai_edit ? (int)$nilai_edit['nilai_keterampilan'] : '' ?>">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Keterangan</label>
                            <input type="text" name="keterangan"
                                   class="form-control" maxlength="50"
                                   value="<?= e($nilai_edit['keterangan'] ?? '') ?>"
                                   placeholder="Contoh: baik">
                        </div>
                    </div>

                    <div class="mt-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <?= $nilai_edit ? 'Simpan Perubahan' : 'Simpan Nilai' ?>
                        </button>

                        <?php if ($nilai_edit): ?>
                            <a href="nilai.php" class="btn btn-secondary">Batal</a>
                        <?php endif; ?>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <h2 class="h4 mb-3">Daftar Nilai</h2>

    <div class="tabel-bingkai">
        <div class="table-responsive">
            <table class="table table-hover tabel-sip">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Mata Pelajaran</th>
                        <th>KKM</th>
                        <th>Pengetahuan</th>
                        <th>Keterampilan</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($data_nilai): ?>
                        <?php foreach ($data_nilai as $i => $nilai): ?>
                            <tr>
                                <td class="tengah"><?= $i + 1 ?></td>
                                <td><?= e($nilai['nis']) ?></td>
                                <td><?= e($nilai['nama_siswa']) ?></td>
                                <td class="tengah"><?= e($nilai['kelas']) ?></td>
                                <td><?= e($nilai['nama_mapel']) ?></td>
                                <td class="tengah"><?= (int)$nilai['nilai_kkm'] ?></td>
                                <td class="tengah"><?= (int)$nilai['nilai_pengetahuan'] ?></td>
                                <td class="tengah"><?= (int)$nilai['nilai_keterampilan'] ?></td>
                                <td><?= e($nilai['keterangan'] ?? '-') ?></td>
                                <td class="tengah">
                                    <a href="nilai.php?id=<?= (int)$nilai['id'] ?>"
                                       class="btn btn-sm btn-info text-white">
                                        Edit
                                    </a>

                                    <form action="aksi_nilai.php" method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus nilai ini?')">
                                        <input type="hidden" name="aksi" value="hapus">
                                        <input type="hidden" name="id"
                                               value="<?= (int)$nilai['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                Belum ada nilai yang tersimpan untuk cakupan akses ini.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require "footer.php"; ?>