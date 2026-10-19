<?php
require "koneksi.php";

$id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);

if ($id === false) {
    die("ID tidak valid.");
}

$data = ambil_satu(
    $koneksi,
    "SELECT users.id, users.username, guru.nip, guru.nama, guru.jenis_kelamin
     FROM users
     JOIN guru ON guru.user_id = users.id
     WHERE users.id = ?",
    "i",
    $id
);

if (!$data) {
    die("Data guru tidak ditemukan.");
}

require "navbar.php";
?>

<div class="container mt-4">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h4>Form Edit User (Guru)</h4>
        </div>
        <div class="card-body">
          <form action="aksi_edit.php" method="POST">

            <input type="hidden" name="id" value="<?= e($data['id']) ?>">

            <h5>Data Profil Guru</h5>

            <div class="mb-3">
              <input type="text" class="form-control" name="nip"
                     placeholder="Nomor Induk Pegawai" value="<?= e($data['nip']) ?>" required>
            </div>

            <div class="mb-3">
              <input type="text" class="form-control" name="nama"
                     placeholder="Nama" value="<?= e($data['nama']) ?>" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Jenis Kelamin:</label>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="jenis_kelamin_guru"
                       id="laki" value="L" <?= ($data['jenis_kelamin'] == 'L') ? 'checked' : '' ?>>
                <label class="form-check-label" for="laki">Laki-laki</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="jenis_kelamin_guru"
                       id="perempuan" value="P" <?= ($data['jenis_kelamin'] == 'P') ? 'checked' : '' ?>>
                <label class="form-check-label" for="perempuan">Perempuan</label>
              </div>
            </div>

            <hr>

            <h5>Data Kredensial Akun (Tabel Users)</h5>

            <div class="mb-3">
              <input type="text" class="form-control" name="username"
                     placeholder="Username" value="<?= e($data['username']) ?>" required>
            </div>

            <div class="mb-3">
              <input type="password" class="form-control" name="password"
                     placeholder="Kosongkan jika tidak ingin mengubah password">
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="daftar_user.php" class="btn btn-secondary">Kembali</a>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require "footer.php"; ?>
