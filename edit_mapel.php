<?php
// Semua logika (termasuk redirect) ada DI ATAS, sebelum navbar.php mengeluarkan HTML.
// Dengan begitu header("Location: ...") tidak kena error "headers already sent".
require "koneksi.php";

$id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);

if ($id === false) {
    header("Location: mapel.php");
    exit();
}

$data_mapel = ambil_satu($koneksi, "SELECT * FROM mapel WHERE id = ?", "i", $id);

if (!$data_mapel) {
    header("Location: mapel.php");
    exit();
}

$daftar_guru = ambil_semua($koneksi, "SELECT id, nama FROM guru ORDER BY nama ASC");

$error      = "";
$kode_mapel = $data_mapel['kode_mapel'];
$nama_mapel = $data_mapel['nama_mapel'];
$guru_id    = $data_mapel['guru_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode_mapel = trim($_POST['kode_mapel'] ?? '');
    $nama_mapel = trim($_POST['nama_mapel'] ?? '');
    $guru_id    = filter_var($_POST['guru_id'] ?? '', FILTER_VALIDATE_INT);

    if ($kode_mapel === '' || $nama_mapel === '' || $guru_id === false) {
        $error = "Semua field wajib diisi.";
        $guru_id = "";
    } elseif (ambil_satu($koneksi, "SELECT id FROM mapel WHERE kode_mapel = ? AND id <> ?", "si", $kode_mapel, $id)) {
        $error = "Kode mapel sudah dipakai mapel lain.";
    } elseif (siapkan(
        $koneksi,
        "UPDATE mapel SET kode_mapel = ?, nama_mapel = ?, guru_id = ? WHERE id = ?",
        "ssii",
        $kode_mapel, $nama_mapel, $guru_id, $id
    )) {
        alert_redirect("Data mapel berhasil diperbarui!", "mapel.php");
    } else {
        $error = "Gagal memperbarui data mapel.";
    }
}

require "navbar.php";
?>

<div class="container mt-4">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h4>Edit Mata Pelajaran</h4>
        </div>
        <div class="card-body">

          <?php if ($error !== ""): ?>
            <div class="alert alert-danger"><?= e($error) ?></div>
          <?php endif; ?>

          <form action="" method="POST">

            <div class="mb-3">
              <label class="form-label fw-semibold">Kode Mapel</label>
              <input type="text" name="kode_mapel" class="form-control"
                     value="<?= e($kode_mapel) ?>" required>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Nama Mapel</label>
              <input type="text" name="nama_mapel" class="form-control"
                     value="<?= e($nama_mapel) ?>" required>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Guru Pengampu</label>
              <select name="guru_id" class="form-select" required>
                <option value="">-- Pilih Guru --</option>
                <?php foreach ($daftar_guru as $guru): ?>
                  <option value="<?= (int)$guru['id'] ?>"
                          <?= ($guru['id'] == $guru_id) ? 'selected' : '' ?>>
                    <?= e($guru['nama']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="d-flex gap-2 mt-4">
              <a href="mapel.php" class="btn btn-secondary w-50">Batal</a>
              <button type="submit" class="btn btn-primary w-50">Update Mapel</button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require "footer.php"; ?>
