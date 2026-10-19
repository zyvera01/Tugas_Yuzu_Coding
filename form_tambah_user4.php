<?php require "navbar.php"; ?>

<!-- container awal -->
<div class="container mt-4">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">

          <form action="aksi_tambah_user.php" method="POST"
                onsubmit="this.querySelector('button[type=submit]').disabled = true;">

            <h4>Data Profil Guru</h4>

            <div class="mb-3">
              <input type="text" class="form-control" name="nip"
                     placeholder="Nomor Induk Pegawai" required>
            </div>

            <div class="mb-3">
              <input type="text" class="form-control" name="nama"
                     placeholder="Nama" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Jenis Kelamin:</label>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="jenis_kelamin_guru"
                       id="laki" value="L" checked>
                <label class="form-check-label" for="laki">Laki-laki</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="jenis_kelamin_guru"
                       id="perempuan" value="P">
                <label class="form-check-label" for="perempuan">Perempuan</label>
              </div>
            </div>

            <hr>

            <h4>Data Kredensial Akun (Tabel Users)</h4>

            <div class="mb-3">
              <input type="text" class="form-control" name="username"
                     placeholder="Username" required>
            </div>

            <div class="mb-3">
              <input type="password" class="form-control" name="password"
                     placeholder="Password" required>
              <input type="hidden" name="role" value="guru">
            </div>

            <button type="submit" class="btn btn-primary">Simpan Data</button>
            <a href="daftar_guru.php" class="btn btn-secondary">Kembali</a>

          </form>

        </div>
      </div>
    </div>
  </div>
</div>
<!-- container akhir -->

<?php require "footer.php"; ?>
