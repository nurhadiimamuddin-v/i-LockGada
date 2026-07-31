<?php
include "atas.php";
?>
<!-- Modal Edit Locker -->
<div class="modal fade" id="modalEditLocker" tabindex="-1" aria-labelledby="modalEditLockerLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditLockerLabel">Edit Locker</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="edit_locker.php" method="POST">
        <div class="modal-body">
          <input type="hidden" id="editIdLocker" name="id_locker">
          <div class="mb-3">
            <label for="editKodeLocker" class="form-label">Kode Locker</label>
            <div class="input-group">
              <span class="input-group-text" id="basic-addon1">LCKR</span>
              <input type="text" class="form-control" id="editKodeLocker" name="kode_locker" 
                     placeholder="00" pattern="[0-9]{2}" title="Masukkan 2 digit angka" required>
            </div>
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
                    <h2>Data Locker</h2>
                    <p class="mb-md-0"> Data akan Memunculkan Locker yang terisi dan belum terisi</p>
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
                    <p class="card-title mb-0">Data Locker</p>
                    <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambahLocker">
                                    </em><span> Tambah</span>
                                </a>
                    </div>
                  <div class="table-responsive">
                  <table id="example" class="display" style="width:100%">
                      <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Locker</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                         <?php
                            $no = 1;
                            include 'config.php';
                            $pegadaian_id = $_SESSION['pegadaian_id'];
                            $sql = mysqli_query($conn, "SELECT * FROM locker  WHERE pegadaian_id = '$pegadaian_id'");
                            while ($data = mysqli_fetch_assoc($sql)) {
                            ?>
                             <tr>
                                 <td><?= $no++; ?></td>
                                 <td><?= $data['kode_locker']; ?> </td>    
                                <td>
                                  <?php
// Cek apakah locker ini sedang digunakan (hanya jika status_barang = 'digadai')
$idLocker = $data['id_locker'];
$cekBarang = mysqli_query($conn, "
    SELECT COUNT(*) as total 
    FROM barang_gadai 
    WHERE locker_id = '$idLocker' AND status_barang = 'digadai'
");
$barang = mysqli_fetch_assoc($cekBarang);
if ($barang['total'] > 0) {
    echo "Sudah Terisi";
} else {
    echo "Belum Terisi";
}
?>

                                </td>
                                 <td>
    <a class="btn" data-bs-toggle="modal" 
    style="font-size: 14px; color: white; padding: 8px 12px; background-color: #20a1ad;" 
            data-bs-target="#modalEditLocker"
            data-id="<?= $data['id_locker'] ?>"
            data-kode="<?= $data['kode_locker'] ?>">
        Edit
    </a>
                                     <a class="btn" href="hapus_locker.php?id_locker=<?= $data['id_locker']; ?>" onclick="return confirm('Yakin Akan Menghapus Data Ini?')"  style="font-size: 14px; color: white; padding: 8px 12px; background-color: #152453;">Hapus</a>
                                 </td>
                                 <?php } ?>
                                </tr>
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
        <h5 class="modal-title" id="modalTambahLockerLabel">Tambah Locker</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="tambah_locker.php" method="POST">
        <div class="modal-body">
          <div class="mb-3">
            <label for="kodeLocker" class="form-label">Kode Locker</label>
            <div class="input-group">
              <span class="input-group-text" id="basic-addon1">LCKR</span>
              <input type="text" class="form-control" id="kodeLocker" name="kode_locker" placeholder="00" required>
            </div>
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

<script>
// JavaScript untuk modal edit
document.addEventListener('DOMContentLoaded', function() {
  var editModal = document.getElementById('modalEditLocker');
  
  editModal.addEventListener('show.bs.modal', function(event) {
    var button = event.relatedTarget;
    var id = button.getAttribute('data-id');
    var kode = button.getAttribute('data-kode');
    
    // Hilangkan prefix LCKR jika ada
    var kodeNumber = kode.replace('LCKR', '');
    
    // Update modal content
    document.getElementById('editIdLocker').value = id;
    document.getElementById('editKodeLocker').value = kodeNumber;
  });
});
</script>
        <?php
        include "bawah.php";
        ?>
