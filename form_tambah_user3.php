<?php require "navbar.php"; ?>

<!-- container awal -->
<div class="container mt-4">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">

          <h4 class="mb-4">Form Isi Data Users</h4>

          <form action="aksi_tambah_user.php" method="POST"
                onsubmit="this.querySelector('button[type=submit]').disabled = true;">

            <div class="mb-3">
              <label class="form-label">Username / Nama Lengkap</label>
              <input type="text" class="form-control" name="username"
                     placeholder="Username/Nama" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="password" class="form-control" name="password"
                     placeholder="Masukkan password akun" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Role</label>
              <select class="form-select" name="role" id="role" required>
                <option value="" selected disabled>-- Pilih Role --</option>
                <option value="admin">Admin</option>
                <option value="guru">Guru</option>
                <option value="siswa">Siswa</option>
              </select>
            </div>

            <!-- Field tambahan khusus GURU -->
            <div id="field-guru" style="display:none;">
              <hr>
              <h5>Data Profil Guru</h5>

              <div class="mb-3">
                <label class="form-label">NIP</label>
                <input type="text" class="form-control" name="nip"
                       placeholder="Nomor Induk Pegawai">
              </div>

              <div class="mb-3">
                <label class="form-label">Jenis Kelamin</label>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="jenis_kelamin_guru"
                         id="guru_laki" value="L" checked>
                  <label class="form-check-label" for="guru_laki">Laki-laki</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="jenis_kelamin_guru"
                         id="guru_perempuan" value="P">
                  <label class="form-check-label" for="guru_perempuan">Perempuan</label>
                </div>
              </div>
            </div>

            <!-- Field tambahan khusus SISWA -->
            <div id="field-siswa" style="display:none;">
              <hr>
              <h5>Data Profil Siswa</h5>

              <div class="mb-3">
                <label class="form-label">NIS</label>
                <input type="text" class="form-control" name="nis"
                       placeholder="Nomor Induk Siswa">
              </div>

              <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" class="form-control" name="nama"
                       placeholder="Nama Siswa">
              </div>

              <div class="mb-3">
                <label class="form-label">Kelas</label>
                <input type="text" class="form-control" name="kelas"
                       placeholder="Kelas Siswa">
              </div>

              <div class="mb-3">
                <label class="form-label">Jenis Kelamin</label>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="jenis_kelamin_siswa"
                         id="siswa_laki" value="L" checked>
                  <label class="form-check-label" for="siswa_laki">Laki-laki</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="jenis_kelamin_siswa"
                         id="siswa_perempuan" value="P">
                  <label class="form-check-label" for="siswa_perempuan">Perempuan</label>
                </div>
              </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-3">Simpan Data</button>

          </form>

        </div>
      </div>
    </div>
  </div>
</div>
<!-- container akhir -->

<script>
  const roleSelect = document.getElementById('role');
  const fieldGuru  = document.getElementById('field-guru');
  const fieldSiswa = document.getElementById('field-siswa');

  // Tampilkan/sembunyikan satu blok. Input di blok yang disembunyikan di-disable
  // (tidak ikut terkirim & tidak kena validasi), sedangkan input teks di blok
  // yang tampil dibuat required.
  function aturBlok(blok, tampil) {
    blok.style.display = tampil ? 'block' : 'none';
    blok.querySelectorAll('input').forEach(function (input) {
      input.disabled = !tampil;
      if (input.type === 'text') {
        input.required = tampil;
      }
    });
  }

  function updateRole() {
    aturBlok(fieldGuru,  roleSelect.value === 'guru');
    aturBlok(fieldSiswa, roleSelect.value === 'siswa');
  }

  roleSelect.addEventListener('change', updateRole);
  updateRole(); // jalankan sekali saat halaman dibuka
</script>

<?php require "footer.php"; ?>
