<?php
require "koneksi.php";

// Hanya akun dengan role guru (digabung dengan data profil di tabel guru)
$data = ambil_semua(
    $koneksi,
    "SELECT users.id, users.username,
            guru.nip, guru.nama, guru.jenis_kelamin
     FROM users
     JOIN guru ON guru.user_id = users.id
     WHERE users.role = 'guru'
     ORDER BY guru.nama ASC"
);

require "navbar.php";
?>

<!-- CSS DataTables (sortir tabel) -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">

<style>
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
  .tabel-pembungkus {
    padding: 1rem;
  }
</style>

<div class="container">
  <div class="text-center">
    <h1 class="fw-normal display-4">Daftar Guru</h1>
    <hr class="border border-dark border-2 opacity-100">
  </div>
</div>

<div class="container mb-4">
  <div class="tabel-bingkai">
    <div class="tabel-pembungkus">
      <div class="table-responsive">
        <table id="tabelGuru" class="table table-hover tabel-sip w-100">
          <thead>
            <tr>
              <th scope="col">No</th>
              <th scope="col">NIP</th>
              <th scope="col">Nama</th>
              <th scope="col">Jenis Kelamin</th>
              <th scope="col">Username</th>
              <th scope="col">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($data as $guru): ?>
            <tr>
              <td class="tengah"></td>
              <td><?= e($guru['nip']) ?></td>
              <td><?= e($guru['nama']) ?></td>
              <td class="tengah"><?= ($guru['jenis_kelamin'] === 'L') ? 'Laki-laki' : 'Perempuan' ?></td>
              <td><?= e($guru['username']) ?></td>
              <td class="tengah">
                <div class="aksi">
                  <a href="form_edit.php?id=<?= (int)$guru['id'] ?>" class="btn btn-sm btn-info text-white">Edit</a>
                  <form action="aksi_hapus.php" method="POST"
                        onsubmit="return confirm('Apakah anda yakin ingin menghapus guru ini?')">
                    <input type="hidden" name="id" value="<?= (int)$guru['id'] ?>">
                    <input type="hidden" name="kembali" value="daftar_guru.php">
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
    var tabel = $('#tabelGuru').DataTable({
      order: [],                       // pakai urutan dari query
      paging: false,
      searching: false,
      lengthChange: false,
      info: false,
      columnDefs: [
        { orderable: false, searchable: false, targets: [0, 5] }   // No & Aksi
      ],
      language: {
        zeroRecords: 'Data tidak ditemukan',
        emptyTable: 'Belum ada data guru.'
      }
    });

    // Nomor urut otomatis, menyesuaikan sortir
    tabel.on('draw.dt', function () {
      tabel.column(0, { page: 'current' }).nodes().each(function (sel, i) {
        sel.innerHTML = i + 1;
      });
    }).draw();
  });
</script>

<?php require "footer.php"; ?>
