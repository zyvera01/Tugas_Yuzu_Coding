<?php
require "koneksi.php";

$data = ambil_semua($koneksi, "SELECT id, username, password, role, created_at FROM users ORDER BY id DESC");

require "navbar.php";
?>

<!-- CSS DataTables (pencarian, sortir, pagination) -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">

<style>
  /* Password hash dipotong supaya tabel tetap rapi; teks penuh muncul saat di-hover */
  .kolom-password {
    max-width: 220px;
    overflow: hidden;
    text-overflow: ellipsis;
    font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
    font-size: 0.8rem;
    color: #6c757d;
  }
  .badge-role {
    text-transform: capitalize;
  }
  .tabel-sip .aksi {
    display: flex;
    gap: 0.35rem;
    justify-content: center;
    flex-wrap: nowrap;
  }
  .tabel-sip .aksi form {
    display: block;
    margin: 0;
  }
  /* Rapikan kontrol bawaan DataTables */
  div.dataTables_wrapper div.dataTables_length select {
    width: auto;
    display: inline-block;
  }
  div.dataTables_wrapper div.dataTables_filter input {
    width: auto;
    display: inline-block;
  }
  .tabel-pembungkus {
    padding: 1rem;
  }
</style>

<div class="container">
  <div class="text-center">
    <h1 class="fw-normal display-4">Daftar User</h1>
    <hr class="border border-dark border-2 opacity-100">
  </div>
</div>

<div class="container mb-4">
  <div class="tabel-bingkai">
    <div class="tabel-pembungkus">
      <div class="table-responsive">
        <table id="tabelUser" class="table table-hover tabel-sip w-100">
          <thead>
            <tr>
              <th scope="col">No</th>
              <th scope="col">Username</th>
              <th scope="col">Password</th>
              <th scope="col">Role</th>
              <th scope="col">Created At</th>
              <th scope="col">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($data as $user): ?>
            <tr>
              <td class="tengah"></td>
              <td><?= e($user['username']) ?></td>
              <td class="kolom-password" title="<?= e($user['password']) ?>"><?= e($user['password']) ?></td>
              <td class="tengah">
                <?php
                  $warna_role = ['admin' => 'text-bg-dark', 'guru' => 'text-bg-primary', 'siswa' => 'text-bg-secondary'];
                  $kelas_role = $warna_role[$user['role']] ?? 'text-bg-light';
                ?>
                <span class="badge badge-role <?= $kelas_role ?>"><?= e($user['role']) ?></span>
              </td>
              <td><?= e($user['created_at']) ?></td>
              <td class="tengah">
                <div class="aksi">
                  <a href="form_edit.php?id=<?= (int)$user['id'] ?>" class="btn btn-sm btn-info text-white">Edit</a>
                  <form action="aksi_hapus.php" method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                    <input type="hidden" name="id" value="<?= (int)$user['id'] ?>">
                    <input type="hidden" name="kembali" value="daftar_user.php">
                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                  </form>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script>
  $(function () {
    var tabel = $('#tabelUser').DataTable({
      order: [],                       // pakai urutan dari query (id terbaru di atas)
      paging: false,                   // tanpa tombol Sebelumnya/Selanjutnya
      searching: false,                // tanpa kolom Cari
      lengthChange: false,             // tanpa "Tampilkan data per halaman"
      info: false,                     // tanpa teks "Menampilkan halaman ..."
      columnDefs: [
        { orderable: false, searchable: false, targets: [0, 5] }   // No & Aksi
      ],
      language: {
        lengthMenu: 'Tampilkan _MENU_ data per halaman',
        search: 'Cari:',
        info: 'Menampilkan halaman _PAGE_ dari _PAGES_',
        infoEmpty: 'Tidak ada data',
        infoFiltered: '(difilter dari _MAX_ data)',
        zeroRecords: 'Data tidak ditemukan',
        emptyTable: 'Belum ada data user.',
        paginate: { previous: 'Sebelumnya', next: 'Selanjutnya' }
      }
    });

    // Nomor urut otomatis, menyesuaikan halaman, pencarian, dan sortir
    tabel.on('draw.dt', function () {
      var mulai = tabel.page.info().start;
      tabel.column(0, { page: 'current' }).nodes().each(function (sel, i) {
        sel.innerHTML = mulai + i + 1;
      });
    }).draw();
  });
</script>

<?php require "footer.php"; ?>
