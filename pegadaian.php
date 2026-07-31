
<?php 
include "head.php";
?>
<!-- Modal Edit Pegadaian -->
<div class="modal fade" id="modalEditLocker" tabindex="-1" aria-labelledby="modalEditLockerLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditLockerLabel">Edit Pegadaian</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="edit_pegadaian.php" method="POST">
        <div class="modal-body">
          <input type="hidden" id="editId" name="id">
          <div class="mb-3">
            <label for="editKodePegadaian" class="form-label">Kode Pegadaian</label>
            <div class="input-group">
              <span class="input-group-text" id="basic-addon1">PGD</span>
              <input type="text" class="form-control" id="editKodePegadaian" name="kode_pegadaian" placeholder="0000" required>
            </div>
          </div>
          <div class="mb-3">
            <label for="editLokasiPegadaian" class="form-label">Lokasi Pegadaian</label>
            <textarea class="form-control" id="editLokasiPegadaian" name="lokasi_pegadaian" placeholder="Jalan, Desa, Kecamatan, Kabupaten, Provinsi" rows="3" required></textarea>
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
                    <h2>Data Pegadaian</h2>
                    <p class="mb-md-0"> Data berbentuk Tabel untuk Menampilkan Data Pegadaian</p>
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
          <p class="card-title mb-0">Data Pegadaian</p>
          <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambahLocker">
            <span> Tambah</span>
          </a>
        </div>
        <div class="table-responsive">
          <table id="example" class="display" style="width:100%">
            <thead>
              <tr>
                <th>No</th>
                <th>Kode Pegadaian</th>
                <th>Lokasi Pegadaian</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              include 'config.php';
              $sql = mysqli_query($conn, "SELECT * FROM pegadaian");
              while ($data = mysqli_fetch_assoc($sql)) {
              ?>
                <tr>
                  <td><?= $no++; ?></td>
                  <td><?= $data['kode_pegadaian']; ?> </td>    
                  <td><?= $data['lokasi_pegadaian']; ?> </td>    
                  <td>
                  <a class="btn edit-btn" 
                     style="font-size: 14px; color: white; padding: 8px 12px; background-color: #20a1ad;" 
                     data-bs-toggle="modal" 
                     data-bs-target="#modalEditLocker"
                     data-id="<?= $data['id']; ?>"
                     data-kode="<?= $data['kode_pegadaian']; ?>"
                     data-lokasi="<?= $data['lokasi_pegadaian']; ?>">
                    Edit
                  </a>
                    <a class="btn" href="hapus_pegadaian.php?id=<?= $data['id']; ?>" onclick="return confirm('Yakin Akan Menghapus Data Ini?')" style="font-size: 14px; color: white; padding: 8px 12px; background-color: #152453;">Hapus</a>
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
<div class="modal fade" id="modalTambahLocker" tabindex="-1" aria-labelledby="modalTambahLockerLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTambahLockerLabel">Tambah Pegadaian</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="tambah_pegadaian.php" method="POST">
        <div class="modal-body">
          <div class="mb-3">
            <label for="kodePegadaian" class="form-label">Kode Pegadaian</label>
            <div class="input-group">
              <span class="input-group-text" id="basic-addon1">PGD</span>
              <input type="text" class="form-control" id="kodePegadaian" name="kode_pegadaian" placeholder="0000" required>
            </div>
          </div>
          <div class="mb-3">
            <label for="lokasiPegadaian" class="form-label">Lokasi Pegadaian</label>
            <textarea class="form-control" id="lokasiPegadaian" name="lokasi_pegadaian" placeholder="Jalan, Desa, Kecamatan, Kabupaten, Provinsi" rows="3" required></textarea>
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
      const lokasi = this.getAttribute('data-lokasi');
      
      // Remove 'PGD' prefix if it exists in the kode
      const kodeWithoutPrefix = kode.startsWith('PGD') ? kode.substring(3) : kode;
      
      document.getElementById('editId').value = id;
      document.getElementById('editKodePegadaian').value = kodeWithoutPrefix;
      document.getElementById('editLokasiPegadaian').value = lokasi;
    });
  });
});
</script>


<?php
include "foot.php";
?>