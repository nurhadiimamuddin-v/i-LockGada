
<?php 
include "head.php";
?>
<!-- Modal Edit Pegadaian -->
<div class="modal fade" id="modalEditJenisBarang" tabindex="-1" aria-labelledby="modalEditJenisBarangLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditJenisBarangLabel">Edit Jenis Barang</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="edit_jenis_barang.php" method="POST">
        <div class="modal-body">
          <input type="hidden" id="editId" name="id_jenis_barang">
          <div class="mb-3">
            <label for="editKodeBarang" class="form-label">Kode Barang</label>
            <div class="input-group">
              <span class="input-group-text" id="basic-addon1">KDBRG</span>
              <input type="text" class="form-control" id="editKodeBarang" name="kode_barang" placeholder="0000" required>
            </div>
          </div>
          <div class="mb-3">
            <label for="editJenisBarang" class="form-label">Jenis Barang</label>
            <input type="text" class="form-control" id="editJenisBarang" name="jenis_barang" placeholder="Jenis Barang" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="d-flex justify-content-between flex-wrap">
                <div class="d-flex align-items-end flex-wrap">
                  <div class="mr-md-3 mr-xl-5">
                    <h2>Data Jenis Barang</h2>
                    <p class="mb-md-0"> Data berbentuk Tabel untuk Menampilkan Data Jenis Barang</p>
                  </div>
                  <div class="d-flex">
                    <img src="images/ty.png" alt="logo" style="height: 63px; margin-left: 550px;" class="d-none d-md-block">
                  </div>
                </div>
               </div>
                
              </div>
            </div>
<div class="row">
  <div class="col-md-12 stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <p class="card-title mb-0">Data Jenis Barang</p>
          <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambahJenisBarang">
            <span> Tambah</span>
          </a>
        </div>
        <div class="table-responsive">
          <table id="example" class="display" style="width:100%">
            <thead>
              <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Jenis Barang</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              include 'config.php';
              $sql = mysqli_query($conn, "SELECT * FROM jenis_barang");
              while ($data = mysqli_fetch_assoc($sql)) {
              ?>
                <tr>
                  <td><?= $no++; ?></td>
                  <td><?= $data['kode_barang']; ?> </td>    
                  <td><?= $data['jenis_barang']; ?> </td>    
                  <td>
                  <a class="btn edit-btn" 
                     style="font-size: 14px; color: white; padding: 8px 12px; background-color: #20a1ad;" 
                     data-bs-toggle="modal" 
                     data-bs-target="#modalEditJenisBarang"
                     data-id="<?= $data['id_jenis_barang']; ?>"
                     data-kode="<?= $data['kode_barang']; ?>"
                     data-jenis="<?= $data['jenis_barang']; ?>">
                    Edit
                  </a>
                    <a class="btn" href="hapus_jenis_barang.php?id_jenis_barang=<?= $data['id_jenis_barang']; ?>" onclick="return confirm('Yakin Akan Menghapus Data Ini?')" style="font-size: 14px; color: white; padding: 8px 12px; background-color: #152453;">Hapus</a>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
  </div>
</div>
</div>
</div>

<!-- Modal Tambah Locker -->
<div class="modal fade" id="modalTambahJenisBarang" tabindex="-1" aria-labelledby="modalTambahJenisBarangLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTambahJenisBarangLabel">Tambah Jenis Barang</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="tambah_jenis_barang.php" method="POST">
        <div class="modal-body">
          <div class="mb-3">
            <label for="kodePegadaian" class="form-label">Kode Barang</label>
            <div class="input-group">
              <span class="input-group-text" id="basic-addon1">KDBRG</span>
              <input type="text" class="form-control" id="kodeBarang" name="kode_barang" placeholder="000" required>
            </div>
          </div>
          <div class="mb-3">
            <label for="jenisBarang" class="form-label">Jenis Barang</label>
            <input type="text" class="form-control" id="JenisBarang" name="jenis_barang" placeholder="Jenis Barang" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- JavaScript to handle edit modal data -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  const editButtons = document.querySelectorAll('.edit-btn');
  
  editButtons.forEach(button => {
    button.addEventListener('click', function() {
      const id = this.getAttribute('data-id');
      const kode = this.getAttribute('data-kode');
      const jenis = this.getAttribute('data-jenis');
      
      // Remove 'PGD' prefix if it exists in the kode
      const kodeWithoutPrefix = kode.startsWith('KDBRG') ? kode.substring(5) : kode;
      
      document.getElementById('editId').value = id;
      document.getElementById('editKodeBarang').value = kodeWithoutPrefix;
      document.getElementById('editJenisBarang').value = jenis;
    });
  });
});
</script>


<?php
include "foot.php";
?>