<?php
require "koneksi.php";

$data = ambil_semua(
    $koneksi,
    "SELECT mapel.*, guru.nama AS nama_guru
     FROM mapel
     LEFT JOIN guru ON mapel.guru_id = guru.id
     ORDER BY mapel.id ASC"
);

require "navbar.php";
?>

<div class="container">
  <div class="container center">
    <h1 class="fw-normal display-4">Data Mapel</h1>
    <hr class="border border-dark border-2 opacity-100">
  </div>
</div>

<div class="container mb-4">
  <div class="tabel-bingkai">
    <div class="table-responsive">
      <table class="table table-hover tabel-sip">
        <thead>
          <tr>
            <th scope="col">No</th>
            <th scope="col">Kode Mapel</th>
            <th scope="col">Nama Mapel</th>
            <th scope="col">Nama Guru</th>
            <th scope="col">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = 1;
          if (count($data) > 0) {
            foreach ($data as $mapel) {
          ?>
          <tr>
            <td class="tengah"><?= $no++ ?></td>
            <td><?= e($mapel['kode_mapel']) ?></td>
            <td><?= e($mapel['nama_mapel']) ?></td>
            <td><?= e($mapel['nama_guru'] ?? '-') ?></td>
            <td class="tengah">
              <a href="edit_mapel.php?id=<?= (int)$mapel['id'] ?>" class="btn btn-sm btn-info text-white">Edit</a>
              <form action="hapus_mapel.php" method="POST"
                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus mapel ini?')">
                <input type="hidden" name="id" value="<?= (int)$mapel['id'] ?>">
                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
              </form>
            </td>
          </tr>
          <?php
            }
          } else {
            echo "<tr><td colspan='5' class='text-center text-muted py-4'>Belum ada data mapel.</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require "footer.php"; ?>
