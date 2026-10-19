<?php
require_once "koneksi.php";

$role = $_SESSION['role'];

$data = ambil_semua(
    $koneksi,
    "SELECT users.id, users.username,
            siswa.nis, siswa.nama, siswa.kelas, siswa.jenis_kelamin
     FROM users
     JOIN siswa ON siswa.user_id = users.id
     WHERE users.role = 'siswa'
     ORDER BY siswa.nama ASC"
);

require "navbar.php";
?>

<div class="container mt-4">
    <div class="text-center mb-4">
        <h1 class="fw-normal display-5">Daftar Siswa</h1>
        <hr class="border border-dark border-2 opacity-100">
        <?php if ($role === 'guru'): ?>
            <p class="text-muted">Mode lihat saja. Data siswa tidak dapat diubah oleh guru.</p>
        <?php endif; ?>
    </div>

    <div class="tabel-bingkai">
        <div class="table-responsive">
            <table class="table table-hover tabel-sip">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Jenis Kelamin</th>
                        <th>Username</th>
                        <?php if ($role === 'admin'): ?>
                            <th>Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($data) > 0): ?>
                        <?php foreach ($data as $i => $siswa): ?>
                            <tr>
                                <td class="tengah"><?= $i + 1 ?></td>
                                <td><?= e($siswa['nis']) ?></td>
                                <td><?= e($siswa['nama']) ?></td>
                                <td class="tengah"><?= e($siswa['kelas']) ?></td>
                                <td class="tengah">
                                    <?= $siswa['jenis_kelamin'] === 'L'
                                        ? 'Laki-laki' : 'Perempuan' ?>
                                </td>
                                <td><?= e($siswa['username']) ?></td>

                                <?php if ($role === 'admin'): ?>
                                    <td class="tengah">
                                        <a href="form_edit.php?id=<?= (int)$siswa['id'] ?>"
                                           class="btn btn-sm btn-info text-white">
                                            Edit
                                        </a>

                                        <form action="aksi_hapus.php" method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus siswa ini?')">
                                            <input type="hidden" name="id"
                                                   value="<?= (int)$siswa['id'] ?>">
                                            <input type="hidden" name="kembali"
                                                   value="daftar_siswa.php">
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="<?= $role === 'admin' ? 7 : 6 ?>"
                                class="text-center py-4">
                                Belum ada data siswa.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require "footer.php"; ?>