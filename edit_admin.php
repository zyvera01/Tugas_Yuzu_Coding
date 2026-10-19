<?php
require "koneksi.php";

$id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);

if ($id === false) {
    die("ID tidak valid.");
}

$admin = ambil_satu($koneksi, "SELECT * FROM users WHERE id = ? AND role = 'admin'", "i", $id);

if (!$admin) {
    die("Data admin tidak ditemukan.");
}

require "navbar.php";
?>

<div class="container mt-4">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h4>Form Edit User (Admin)</h4>
        </div>
        <div class="card-body">
          <form action="aksi_edit.php" method="POST">

            <input type="hidden" name="id" value="<?= e($admin['id']) ?>">

            <h5>Data Kredensial Akun</h5>

            <div class="mb-3">
              <label class="form-label">Username</label>
              <input type="text" class="form-control" name="username"
                     placeholder="Username" value="<?= e($admin['username']) ?>" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Password Baru</label>
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
