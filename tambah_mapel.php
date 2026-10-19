<?php
require "koneksi.php";

$daftar_guru = ambil_semua($koneksi, "SELECT id, nama FROM guru ORDER BY nama ASC");

$error      = "";
$kode_mapel = "";
$nama_mapel = "";
$guru_id    = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode_mapel = trim($_POST['kode_mapel'] ?? '');
    $nama_mapel = trim($_POST['nama_mapel'] ?? '');
    $guru_id    = filter_var($_POST['guru_id'] ?? '', FILTER_VALIDATE_INT);

    if ($kode_mapel === '' || $nama_mapel === '' || $guru_id === false) {
        $error = "Semua field wajib diisi.";
        $guru_id = "";
    } elseif (ambil_satu($koneksi, "SELECT id FROM mapel WHERE kode_mapel = ?", "s", $kode_mapel)) {
        $error = "Kode mapel sudah dipakai.";
    } elseif (siapkan(
        $koneksi,
        "INSERT INTO mapel (kode_mapel, nama_mapel, guru_id) VALUES (?, ?, ?)",
        "ssi",
        $kode_mapel, $nama_mapel, $guru_id
    )) {
        alert_redirect("Data mapel berhasil ditambahkan!", "mapel.php");
    } else {
        $error = "Gagal menyimpan data mapel.";
    }
}

require "navbar.php";
?>

<div class="container mt-4">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h4>Tambah Mata Pelajaran</h4>
        </div>
        <div class="card-body">

          <?php if ($error !== ""): ?>
            <div class="alert alert-danger"><?= e($error) ?></div>
          <?php endif; ?>

          <form action="" method="POST">

            <div class="mb-3">
              <label class="form-label fw-semibold">Kode Mapel</label>
              <input type="text" name="kode_mapel" class="form-control"
                     placeholder="Contoh: kk00" value="<?= e($kode_mapel) ?>" required>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Nama Mapel</label>
              <input type="text" name="nama_mapel" class="form-control"
                     placeholder="Masukkan nama mata pelajaran" value="<?= e($nama_mapel) ?>" required>
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

            <button type="submit" class="btn btn-primary w-100 mt-3">Simpan Mapel</button>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require "footer.php"; ?>
