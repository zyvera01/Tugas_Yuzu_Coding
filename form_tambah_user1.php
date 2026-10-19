<?php require "navbar.php"; ?>

<!-- container awal -->
<div class="container mt-4">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">

          <h4>Form Tambah User (Admin)</h4>

          <form action="aksi_tambah_user.php" method="POST"
                onsubmit="this.querySelector('button[type=submit]').disabled = true;">

            <h5>Data Kredensial Akun</h5>

            <div class="mb-3">
              <label class="form-label">Username</label>
              <input type="text" class="form-control" name="username"
                     placeholder="Username" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="password" class="form-control" name="password"
                     placeholder="Password" required>
              <input type="hidden" name="role" value="admin">
            </div>

            <button type="submit" class="btn btn-primary">Simpan Data</button>
            <a href="daftar_user.php" class="btn btn-secondary">Kembali</a>

          </form>

        </div>
      </div>
    </div>
  </div>
</div>
<!-- container akhir -->

<?php require "footer.php"; ?>
